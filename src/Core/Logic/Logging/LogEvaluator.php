<?php

namespace Phine\Bundles\Core\Logic\Logging;

use Phine\Database\Core\LogItem;
use Phine\Database\Core\LogPage;
use Phine\Database\Core\LogContent;
use Phine\Database\Core\LogLayout;
use Phine\Database\Core\LogArea;
use Phine\Database\Core\LogContainer;
use Phine\Database\Core\LogTemplate;
use Phine\Database\Core\PageContent;
use Phine\Database\Core\ContainerContent;
use Phine\Database\Core\Container;
use Phine\Database\Core\LayoutContent;
use Phine\Database\Core\Content;
use Phine\Database\Core\Area;
use Phine\Database\Core\Page;
use Phine\Database\Core\Layout;
use Phine\Database\Access;
use Phine\Framework\Database\Sql\JoinType;

/**
 * Provides helper methods to evaluate special loggings
 */
class LogEvaluator
{

    /**
     * Gets the last page modification log item
     * @param Page $page The page
     * @param Interfaces\IContainerReferenceResolver $resolver
     * @return LogItem Returns the last log item that changed anything related to the page
     */
    static function LastPageModLog(Page $page, Interfaces\IContainerReferenceResolver $resolver)
    {
        $lastLog = self::LastPageLog($page);
        $pageContents = PageContent::Schema()->FetchByPage(false, $page);
        foreach ($pageContents as $pageContent)
        {
            $content = $pageContent->GetContent();
            $contentLog = self::LastContentModLog($content, $resolver);
            if (self::IsAfter($contentLog, $lastLog))
            {
                $lastLog = $contentLog;
            }
        }
        $layoutLog = self::LastLayoutLog($page->GetLayout());
        if (self::IsAfter($layoutLog, $lastLog))
        {
            $lastLog = $layoutLog;
        }
        return $lastLog;
    }

    /**
     * Gets the last container modification log item
     * @param Container $container The container
     * @return LogItem Returns the last log item that changed anything related to the container
     */
    static function LastContainerModLog(Container $container)
    {
        $lastLog = self::LastContainerLog($container);
        $containerContents = ContainerContent::Schema()->FetchByContainer(false, $container);
        foreach ($containerContents as $containerContent)
        {
            $content = $containerContent->GetContent();
            $contentLog = self::LastContentModLog($content, null);
            if (self::IsAfter($contentLog, $lastLog))
            {
                $lastLog = $contentLog;
            }
        }
        return $lastLog;
    }

    /**
     * Gets the last layout modification log item
     * @param Layout $layout The layout
     * @return LogItem Returns the last log item that changed anything related to the layout
     */
    static function LastLayoutModLog(Layout $layout)
    {
        $lastLog = $this->LastLayoutLog($layout);
        $areas = Area::Schema()->FetchByLayout(false, $layout);
        foreach ($areas as $area)
        {
            $areaLog = self::LastAreaModLog($area);
            if (self::IsAfter($areaLog, $lastLog))
            {
                $lastLog = $areaLog;
            }
        }
        return $lastLog;
    }

    /**
     * Gets the last area modification log item
     * @param Area $area The area
     * @return LogItem Returns the last log item that changed anything related to the area
     */
    static function LastAreaModLog(Area $area, Interfaces\IContainerReferenceResolver $resolver)
    {
        $lastLog = $this->LastAreaLog($area);
        $areaContents = LayoutContent::Schema()->FetchByArea(false, $area);
        foreach ($areaContents as $areaContent)
        {
            $content = $areaContent->GetContent();
            $contentLog = self::LastContentModLog($content, $resolver);
            if (self::IsAfter($contentLog, $lastLog))
            {
                $lastLog = $contentLog;
            }
        }
        return $lastLog;
    }

    /**
     * Gets the last content modification log item
     * @param Content $content The content
     * @return LogItem Returns the last log item that changed anything related to the content
     */
    function LastContentModLog(Content $content, Interfaces\IContainerReferenceResolver $resolver = null)
    {
        $lastLog = self::LastContentLog($content);
        $container = $resolver ? $resolver->GetReferencedContainer($content) : null;
        if ($container)
        {
            $containerLog = self::LastContainerModLog($container);
            if (self::IsAfter($containerLog, $lastLog))
            {
                $lastLog = $containerLog;
            }
        }
        if ($content->GetTemplate())
        {
            $templateLog = self::LastTemplateLog($content->GetType(), $content->GetTemplate());
            if (self::IsAfter($templateLog, $lastLog))
            {
                $lastLog = $templateLog;
            }
        }
        return $lastLog;
    }

    /**
     * Compares two log item by date
     * @param LogItem $lhs The left hand side
     * @param LogItem $rhs The right hand side
     * @return boolean Returns trie if right hand side is null and lhs is not, or change date of lhs is after change date of rhs
     */
    static function IsAfter(LogItem $lhs = null, LogItem $rhs = null)
    {
        if (!$lhs)
        {
            return false;
        }
        if (!$rhs)
        {
            return true;
        }
        return $lhs->GetChanged()->IsAfter($rhs->GetChanged());
    }

    /**
     * The last log item that is directly related to the page
     * @param Page $page The page
     * @return LogItem Returns the log item
     */
    static function LastPageLog(Page $page)
    {
        $tblLogPage = LogPage::Schema()->Table();
        $tblLogItem = LogItem::Schema()->Table();
        $sql = Access::SqlBuilder();
        $orderBy = $sql->OrderList($sql->OrderDesc($tblLogItem->Field('Changed')));
        $joinCond = $sql->Equals($tblLogPage->Field('LogItem'), $tblLogItem->Field('ID'));
        $where = $sql->Equals($tblLogPage->Field('Page'), $sql->Value($page->GetID()));
        return LogItem::Schema()->First($where, $orderBy, null, $sql->Join($tblLogPage), JoinType::Inner(), $joinCond);
    }

    /**
     * The last log item that is directly related to the content
     * @param Content $content The content
     * @return LogItem Returns the log item
     */
    static function LastContentLog(Content $content)
    {
        $tblLogContent = LogContent::Schema()->Table();
        $tblLogItem = LogItem::Schema()->Table();
        $sql = Access::SqlBuilder();
        $orderBy = $sql->OrderList($sql->OrderDesc($tblLogItem->Field('Changed')));
        $joinCond = $sql->Equals($tblLogContent->Field('LogItem'), $tblLogItem->Field('ID'));
        $where = $sql->Equals($tblLogContent->Field('Content'), $sql->Value($content->GetID()));
        return LogItem::Schema()->First($where, $orderBy, null, $sql->Join($tblLogContent), JoinType::Inner(), $joinCond);
    }

    /**
     * The last log item that is directly related to the layout
     * @param Layout $layout The layout
     * @return LogItem Returns the log item
     */
    static function LastLayoutLog(Layout $layout)
    {
        $tblLogLayout = LogLayout::Schema()->Table();
        $tblLogItem = LogItem::Schema()->Table();
        $sql = Access::SqlBuilder();
        $orderBy = $sql->OrderList($sql->OrderDesc($tblLogItem->Field('Changed')));
        $joinCond = $sql->Equals($tblLogLayout->Field('LogItem'), $tblLogItem->Field('ID'));
        $where = $sql->Equals($tblLogLayout->Field('Layout'), $sql->Value($layout->GetID()));
        return LogItem::Schema()->First($where, $orderBy, null, $sql->Join($tblLogLayout), JoinType::Inner(), $joinCond);
    }

    /**
     * The last log item that is directly related to the area
     * @param Area $area The area
     * @return LogItem Returns the log item
     */
    static function LastAreaLog(Area $area)
    {
        $tblLogArea = LogArea::Schema()->Table();
        $tblLogItem = LogItem::Schema()->Table();
        $sql = Access::SqlBuilder();
        $orderBy = $sql->OrderList($sql->OrderDesc($tblLogItem->Field('Changed')));
        $joinCond = $sql->Equals($tblLogArea->Field('LogItem'), $tblLogItem->Field('ID'));
        $where = $sql->Equals($tblLogArea->Field('Area'), $sql->Value($area->GetID()));
        return LogItem::Schema()->First($where, $orderBy, null, $sql->Join($tblLogArea), JoinType::Inner(), $joinCond);
    }

    /**
     * The last log item that is directly related to the container
     * @param Container $container The container
     * @return LogItem Returns the log item
     */
    static function LastContainerLog(Container $container)
    {
        $tblLogContainer = LogContainer::Schema()->Table();
        $tblLogItem = LogItem::Schema()->Table();
        $sql = Access::SqlBuilder();
        $orderBy = $sql->OrderList($sql->OrderDesc($tblLogItem->Field('Changed')));
        $joinCond = $sql->Equals($tblLogContainer->Field('LogItem'), $tblLogItem->Field('ID'));
        $where = $sql->Equals($tblLogContainer->Field('Container'), $sql->Value($container->GetID()));
        return LogItem::Schema()->First($where, $orderBy, null, $sql->Join($tblLogContainer), JoinType::Inner(), $joinCond);
    }

    /**
     * The last log item that is directly related to the template
     * @param Content $content The content
     * @return LogItem Returns the log item
     */
    static function LastTemplateLog($moduleType, $template)
    {
        $tblLogTemplate = \Phine\Database\Core\LogTemplate::Schema()->Table();
        $tblLogItem = LogItem::Schema()->Table();
        $sql = Access::SqlBuilder();
        $orderBy = $sql->OrderList($sql->OrderDesc($tblLogItem->Field('Changed')));
        $joinCond = $sql->Equals($tblLogTemplate->Field('LogItem'), $tblLogItem->Field('ID'));
        $where = $sql->Equals($tblLogTemplate->Field('Template'), $sql->Value($template))
                ->And_($sql->Equals($tblLogTemplate->Field('ModuleType'), $moduleType));
        return LogItem::Schema()->First($where, $orderBy, null, $sql->Join($tblLogTemplate), JoinType::Inner(), $joinCond);
    }

}
