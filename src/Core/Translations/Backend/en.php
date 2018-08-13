<?php

use Phine\Framework\Localization\PhpTranslator;
$translator = PhpTranslator::Singleton();
$lang = 'en';

//date & numeric formats
$translator->AddTranslation($lang, 'Core.DateFormat', 'm/d/Y');
$translator->AddTranslation($lang, 'Core.DecimalSeparator', '.');
$translator->AddTranslation($lang, 'Core.ThousandsSeparator', ',');

//login
$translator->AddTranslation($lang, 'Core.Login.Legend', 'Login');
$translator->AddTranslation($lang, 'Core.Login.Name', 'User name');
$translator->AddTranslation($lang, 'Core.Login.Password', 'Password');
$translator->AddTranslation($lang, 'Core.Login.Submit', 'Submit');

$translator->AddTranslation($lang, 'Core.Login.Failed', 'No valid backend user data provided');

//common texts
$translator->AddTranslation($lang, 'Core.PleaseSelect', '-- please select --');

//common enums
//-- sitemap change frequency

$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Always', 'Always');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Hourly', 'Hourly');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Daily', 'Daily');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Weekly', 'Weekly');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Monthly', 'Monthly');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Yearly', 'Yearly');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Never', 'Never');

// --> deletion modal
$translator->AddTranslation($lang, 'Core.ModalDelete.Title', 'Delete Confirmation');
$translator->AddTranslation($lang, 'Core.ModalDelete.Description.Name_{0}', "Do you really want to delete '{0}' and all of its dependencies?");
$translator->AddTranslation($lang, 'Core.ModalDelete.Cancel', 'No, cancel!');
$translator->AddTranslation($lang, 'Core.ModalDelete.Confirm', 'Yes, delete!');

//login

//site list
$translator->AddTranslation($lang, 'Core.SiteList.Title', 'Websites');
$translator->AddTranslation($lang, 'Core.SiteList.Description.Amount_{0}', 'There are {0} websites controlled by this Phine installation.<br />Click the <strong>pages symbol</strong> to edit the pages for a website');
$translator->AddTranslation($lang, 'Core.SiteList.New', 'Create Website');
$translator->AddTranslation($lang, 'Core.SiteList.Name', 'Site Name');
$translator->AddTranslation($lang, 'Core.SiteList.PageTree', 'Show page tree');
$translator->AddTranslation($lang, 'Core.SiteList.Edit', 'Edit website settings');
$translator->AddTranslation($lang, 'Core.SiteList.Delete', 'Delete this website');


//site form
$translator->AddTranslation($lang, 'Core.SiteForm.Title', 'Edit Website');

$translator->AddTranslation($lang, 'Core.SiteForm.Description', 'You can adjust the basic settings of the website using this form.');
$translator->AddTranslation($lang, 'Core.SiteForm.Legend', 'Website Settings');
$translator->AddTranslation($lang, 'Core.SiteForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.SiteForm.Url', 'URL');
$translator->AddTranslation($lang, 'Core.SiteForm.NoGroup', '-- No group --');
$translator->AddTranslation($lang, 'Core.SiteForm.UserGroup', 'Owner Group & Rights');
$translator->AddTranslation($lang, 'Core.SiteForm.Submit', 'Save');
$translator->AddTranslation($lang, 'Core.SiteForm.Name.Validation.Required.Missing', 'Insert a unique site name');
$translator->AddTranslation($lang, 'Core.SiteForm.Url.Validation.Required.Missing', 'Define the website url');
$translator->AddTranslation($lang, 'Core.SiteForm.Name.Validation.DatabaseCount.TooMuch', 'This name is already in use');
$translator->AddTranslation($lang, 'Core.SiteForm.Url.Validation.PhpFilter.InvalidUrl', 'This is not a valid url');
$translator->AddTranslation($lang, 'Core.SiteForm.Language', 'Language');
$translator->AddTranslation($lang, 'Core.SiteForm.Language.Validation.Required.Missing', 'Please select language');

$translator->AddTranslation($lang, 'Core.SiteForm.Access.Legend', 'Access Rights');
$translator->AddTranslation($lang, 'Core.SiteForm.Sitemap.Legend', 'Sitemap Settings');
$translator->AddTranslation($lang, 'Core.SiteForm.SitemapActive', 'Create Sitemap');
$translator->AddTranslation($lang, 'Core.SiteForm.SitemapCacheLifetime', 'Sitemap Lifetime (in seconds)');
$translator->AddTranslation($lang, 'Core.SiteForm.SitemapCacheLifetime.Validation.Integer.HasNonDigits', 'This is not a valid number');
//layout list
$translator->AddTranslation($lang, 'Core.LayoutList.Title', 'Layout List');
$translator->AddTranslation($lang, 'Core.LayoutList.Description.Amount_{0}', 'There are currently {0} layouts available.');
$translator->AddTranslation($lang, 'Core.LayoutList.Name', 'Layout Name');
$translator->AddTranslation($lang, 'Core.LayoutList.EditArea', 'Click for layout areas');
$translator->AddTranslation($lang, 'Core.LayoutList.Edit', 'Edit layout settings');
$translator->AddTranslation($lang, 'Core.LayoutList.EditTemplate', 'Edit layout template');

$translator->AddTranslation($lang, 'Core.LayoutList.New', 'Create new layout');
$translator->AddTranslation($lang, 'Core.LayoutList.Delete', 'Delete layout');

//layout form
$translator->AddTranslation($lang, 'Core.LayoutForm.Title', 'Edit Layout');
$translator->AddTranslation($lang, 'Core.LayoutForm.Description', 'You can edit the basic layout settings, here.');

$translator->AddTranslation($lang, 'Core.LayoutForm.Legend', 'Settings');
$translator->AddTranslation($lang, 'Core.LayoutForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.LayoutForm.Areas', 'Area Names');
$translator->AddTranslation($lang, 'Core.LayoutForm.Areas.Placeholder', 'Comma separated, e.g. Top, Main, Left, Right');
$translator->AddTranslation($lang, 'Core.LayoutForm.UserGroup', 'Owner Group & Rights');

$translator->AddTranslation($lang, 'Core.LayoutForm.NoGroup', '-- No group --');
$translator->AddTranslation($lang, 'Core.LayoutForm.Submit', 'Save & Continue');
$translator->AddTranslation($lang, 'Core.LayoutForm.Name.Validation.Required.Missing', 'Insert a unique name');
$translator->AddTranslation($lang, 'Core.LayoutForm.Areas.Validation.Required.Missing', 'Insert at least one area name');

$translator->AddTranslation($lang, 'Core.LayoutForm.Name.Validation.DatabaseCount.TooMuch', 'This name is already in use');

//layout template form
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Title', 'Edit Layout Template');
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Description', 'Edit the template code of your layout and place the areas to the correct html blocks.');
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Legend', 'Layout Template');
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Contents', 'Template Code');
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Submit', 'Save');

// area list
$translator->AddTranslation($lang, 'Core.AreaList.Title', 'Layout areas');
$translator->AddTranslation($lang, 'Core.AreaList.Description', 'Edit the areas of the layout, here.');
$translator->AddTranslation($lang, 'Core.AreaList.CreateStartArea', 'Create layout area on top');
$translator->AddTranslation($lang, 'Core.AreaList.CreateAfter', 'New area after this');
$translator->AddTranslation($lang, 'Core.AreaList.Cut', 'Cut area for moving');
$translator->AddTranslation($lang, 'Core.AreaList.InsertAfter', 'Insert cut area after this');
$translator->AddTranslation($lang, 'Core.AreaList.InsertIn', 'Insert cut area on top');
$translator->AddTranslation($lang, 'Core.AreaList.Back', 'Back to layout list');
$translator->AddTranslation($lang, 'Core.AreaList.CancelCut', 'Cancel moving area');
$translator->AddTranslation($lang, 'Core.AreaList.Edit', 'Change name & lock status');

// area form
$translator->AddTranslation($lang, 'Core.AreaForm.Title', 'Edit Area');
$translator->AddTranslation($lang, 'Core.AreaForm.Description', 'Set up the area properties name and lock status, here.');
$translator->AddTranslation($lang, 'Core.AreaForm.Legend', 'Area Settings');
$translator->AddTranslation($lang, 'Core.AreaForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.AreaForm.Name.Validation.Required.Missing', 'Insert layout area name');
$translator->AddTranslation($lang, 'Core.AreaForm.Name.Validation.DatabaseCount.TooMuch', 'Area name already in use');
$translator->AddTranslation($lang, 'Core.AreaForm.Locked', 'Lock For Owner Group');
$translator->AddTranslation($lang, 'Core.AreaForm.Submit', 'Save');

// container list
$translator->AddTranslation($lang, 'Core.ContainerList.Title', 'Container List');
$translator->AddTranslation($lang, 'Core.ContainerList.Description.Amount_{0}', 'Containers are used to store contents that can be used arbitrarily in various places of your web pages. There are currently {0} containers.');
$translator->AddTranslation($lang, 'Core.ContainerList.New', 'Create New Container');
$translator->AddTranslation($lang, 'Core.ContainerList.Name', 'Container Name');
$translator->AddTranslation($lang, 'Core.ContainerList.EditContents', 'Edit container contents');
$translator->AddTranslation($lang, 'Core.ContainerList.Edit', 'Edit container settings');
$translator->AddTranslation($lang, 'Core.ContainerList.Delete', 'Delete container');

//container form
$translator->AddTranslation($lang, 'Core.ContainerForm.Title', 'Edit Container');
$translator->AddTranslation($lang, 'Core.ContainerForm.Description', 'Set up the container properties, here.');
$translator->AddTranslation($lang, 'Core.ContainerForm.Legend', 'Container Settings');
$translator->AddTranslation($lang, 'Core.ContainerForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.ContainerForm.Name.Validation.Required.Missing', 'Insert container name');
$translator->AddTranslation($lang, 'Core.ContainerForm.Name.Validation.DatabaseCount.TooMuch', 'Container name already in use');
$translator->AddTranslation($lang, 'Core.ContainerForm.UserGroup', 'Owner Group & Rights');
$translator->AddTranslation($lang, 'Core.ContainerForm.NoGroup', '-- No group --');
$translator->AddTranslation($lang, 'Core.ContainerForm.Submit', 'Save');

// page tree
$translator->AddTranslation($lang, 'Core.PageTree.Title', 'Page Tree');
$translator->AddTranslation($lang, 'Core.PageTree.Description', 'Edit pages and their contents for the current website.');
$translator->AddTranslation($lang, 'Core.PageTree.CreateStartPage', 'Create new start page');
$translator->AddTranslation($lang, 'Core.PageTree.CreateIn', 'Create page in this one');
$translator->AddTranslation($lang, 'Core.PageTree.CreateAfter', 'Create page after this one');
$translator->AddTranslation($lang, 'Core.PageTree.InsertIn', 'Inserte page in this one');
$translator->AddTranslation($lang, 'Core.PageTree.InsertAfter', 'Insert page after this one');
$translator->AddTranslation($lang, 'Core.PageTree.Cut', 'Cut page for moving');
$translator->AddTranslation($lang, 'Core.PageTree.CancelCut', 'Cancel moving the page');
$translator->AddTranslation($lang, 'Core.PageTree.Edit', 'Edit page settings');
$translator->AddTranslation($lang, 'Core.PageTree.EditArea', 'Click to view areas');
$translator->AddTranslation($lang, 'Core.PageTree.Delete', 'Delete page');

//page form
$translator->AddTranslation($lang, 'Core.PageForm.Headline', 'Edit Page');
$translator->AddTranslation($lang, 'Core.PageForm.Explanation', 'Define url, name, access and other page properties, here.');
$translator->AddTranslation($lang, 'Core.PageForm.Legend', 'Page Properties');
$translator->AddTranslation($lang, 'Core.PageForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.PageForm.Url', 'URL');
$translator->AddTranslation($lang, 'Core.PageForm.Layout', 'Layout');
$translator->AddTranslation($lang, 'Core.PageForm.Title', 'Title');
$translator->AddTranslation($lang, 'Core.PageForm.Description', 'Description');
$translator->AddTranslation($lang, 'Core.PageForm.Keywords', 'Keywords');
$translator->AddTranslation($lang, 'Core.PageForm.MenuAccess', 'Menu Visibility');
$translator->AddTranslation($lang, 'Core.PageForm.Publish', 'Publish');
$translator->AddTranslation($lang, 'Core.PageForm.PublishFromDate', 'Vislible from');
$translator->AddTranslation($lang, 'Core.PageForm.PublishFromHour', 'Hrs.');
$translator->AddTranslation($lang, 'Core.PageForm.PublishFromMinute', 'Min.');
$translator->AddTranslation($lang, 'Core.PageForm.PublishToDate', 'Visible to');
$translator->AddTranslation($lang, 'Core.PageForm.PublishToHour', 'Hrs.');
$translator->AddTranslation($lang, 'Core.PageForm.PublishToMinute', 'Min.');

$translator->AddTranslation($lang, 'Core.PageForm.Name.Validation.Required.Missing', 'Insert page name');
$translator->AddTranslation($lang, 'Core.PageForm.Name.Validation.DatabaseCount.TooMuch', 'This name is already in use');
$translator->AddTranslation($lang, 'Core.PageForm.Url.Validation.Required.Missing', 'Add relative page url');
$translator->AddTranslation($lang, 'Core.PageForm.Layout.Validation.Required.Missing', 'Select page layout');

$translator->AddTranslation($lang, 'Core.PageForm.Type.Legend', 'Mode');
$translator->AddTranslation($lang, 'Core.PageForm.Type', 'Page Type');
$translator->AddTranslation($lang, 'Core.PageForm.Type.Normal', 'Regular Page');
$translator->AddTranslation($lang, 'Core.PageForm.Type.RedirectPermanent', 'Permanent Redirect (301)');
$translator->AddTranslation($lang, 'Core.PageForm.Type.RedirectTemporary', 'Temporary Redirect (302)');
$translator->AddTranslation($lang, 'Core.PageForm.Type.NotFound', '404 Page (URL not found)');
$translator->AddTranslation($lang, 'Core.PageForm.RedirectTarget', 'Redirect Target');
$translator->AddTranslation($lang, 'Core.PageForm.RedirectTarget.Validation.Required.Missing', 'Target page must be selected');

$translator->AddTranslation($lang, 'Core.PageForm.Access.Legend', 'Access & Visibility');
$translator->AddTranslation($lang, 'Core.PageForm.MenuAccess.Authorized', 'For authorized users, only (default)');
$translator->AddTranslation($lang, 'Core.PageForm.MenuAccess.AlwaysVisible', 'Always visible');
$translator->AddTranslation($lang, 'Core.PageForm.MenuAccess.AlwaysHidden', 'Always hidden');

$translator->AddTranslation($lang, 'Core.PageForm.UserGroup', 'Owner Group & Rights');
$translator->AddTranslation($lang, 'Core.PageForm.UserGroup.Inherit_{0}', '-- Inherit ({0}) --');
$translator->AddTranslation($lang, 'Core.PageForm.UserGroup.Inherit', '-- Inherit (No group) --');

$translator->AddTranslation($lang, 'Core.PageForm.Sitemap.Legend', 'Sitemap Entry');
$translator->AddTranslation($lang, 'Core.PageForm.SitemapRelevance', 'Relevance (0.0 = omit entry)');
$translator->AddTranslation($lang, 'Core.PageForm.SitemapChangeFrequency', 'Change Frequency');
$translator->AddTranslation($lang, 'Core.PageForm.Submit', 'Save');

//page content tree
$translator->AddTranslation($lang, 'Core.PageContentTree.Title', 'Page Contents');
$translator->AddTranslation($lang, 'Core.PageContentTree.Description', 'With the proper rights assigned, you can edit, delete or move the page contents here.');
$translator->AddTranslation($lang, 'Core.PageContentTree.CreateStartElement', 'Create top content element');
$translator->AddTranslation($lang, 'Core.PageContentTree.Edit', 'Edit content element');
$translator->AddTranslation($lang, 'Core.PageContentTree.Delete', 'Delete content element');
$translator->AddTranslation($lang, 'Core.PageContentTree.Cut', 'Cut element for moving');
$translator->AddTranslation($lang, 'Core.PageContentTree.CreateIn', 'Create element in here');
$translator->AddTranslation($lang, 'Core.PageContentTree.CreateAfter', 'Create element after this one');
$translator->AddTranslation($lang, 'Core.PageContentTree.InsertIn', 'Insert element in this one');
$translator->AddTranslation($lang, 'Core.PageContentTree.InsertAfter', 'Insert element after this one');
$translator->AddTranslation($lang, 'Core.PageContentTree.CancelCut', 'Cancel moving element');

//layout content tree
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Title', 'Layout Contents');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Description', 'With the proper rights assigned, you can edit, delete or move the area contents here.');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.CreateStartElement', 'Create top content element');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Edit', 'Edit content element');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Delete', 'Delete content element');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Cut', 'Cut element for moving');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.CreateIn', 'Create element in here');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.CreateAfter', 'Create element after this one');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.InsertIn', 'Insert element in this one');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.InsertAfter', 'Insert element after this one');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.CancelCut', 'Cancel moving element');

//container content tree
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Title', 'Container Contents');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Description', 'With the proper rights assigned, you can edit, delete or move the container contents here.');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.CreateStartElement', 'Create top content element');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Edit', 'Edit content element');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Delete', 'Delete content element');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Cut', 'Cut element for moving');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.CreateIn', 'Create element in here');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.CreateAfter', 'Create element after this one');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.InsertIn', 'Insert element in this one');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.InsertAfter', 'Insert element after this one');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.CancelCut', 'Cancel moving element');
//User group list
$translator->AddTranslation($lang, 'Core.UsergroupList.Title', 'User Groups');
$translator->AddTranslation($lang, 'Core.UsergroupList.Description.Amount_{0}', 'At this moment, there exist {0} groups for the phine backend.');
$translator->AddTranslation($lang, 'Core.UsergroupList.New', 'Create New Group');
$translator->AddTranslation($lang, 'Core.UsergroupList.Name', 'Group Name');
$translator->AddTranslation($lang, 'Core.UsergroupList.Edit', 'Edit group properties');
$translator->AddTranslation($lang, 'Core.UsergroupList.Delete', 'Remove user group');
$translator->AddTranslation($lang, 'Core.UsergroupList.LockModules', 'Lock/Release modules in the group backend');

//User List
$translator->AddTranslation($lang, 'Core.UserList.Title', 'User List');
$translator->AddTranslation($lang, 'Core.UserList.Description.Amount_{0}', 'There are currently {0} backend user who can access this phine installation.');
$translator->AddTranslation($lang, 'Core.UserList.New', 'Create New User');
$translator->AddTranslation($lang, 'Core.UserList.Name', 'Name');
$translator->AddTranslation($lang, 'Core.UserList.IsAdmin', 'Is Administrator?');
$translator->AddTranslation($lang, 'Core.UserList.EditGroups', 'Add/delete user groups');
$translator->AddTranslation($lang, 'Core.UserList.Edit', 'Edit user');
$translator->AddTranslation($lang, 'Core.UserList.Delete', 'Delete user');

//user group form
$translator->AddTranslation($lang, 'Core.UsergroupForm.Title', 'Edit User Group');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Description', 'Set the group name and basic access rights.');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Legend', 'User Group Settings');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Name', 'User Group Name');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Name.Validation.Required.Missing', 'Insert the name of the group');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Name.Validation.DatabaseCount.TooMuch', 'This name is already in use');
$translator->AddTranslation($lang, 'Core.UsergroupForm.CreateSites', 'Can Create Sites');
$translator->AddTranslation($lang, 'Core.UsergroupForm.CreateLayouts', 'Can Create Layouts');
$translator->AddTranslation($lang, 'Core.UsergroupForm.CreateContainers', 'Can Create Containers');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Submit', 'Save');

//user form
$translator->AddTranslation($lang, 'Core.UserForm.Title', 'Edit User');
$translator->AddTranslation($lang, 'Core.UserForm.Description', 'Adjust the user name, password and other basic settings, here.');
$translator->AddTranslation($lang, 'Core.UserForm.Legend', 'User Settigs');
$translator->AddTranslation($lang, 'Core.UserForm.Name', 'Login Name');
$translator->AddTranslation($lang, 'Core.UserForm.EMail', 'E-Mail');
$translator->AddTranslation($lang, 'Core.UserForm.Language', 'Language');
$translator->AddTranslation($lang, 'Core.UserForm.IsAdmin', 'Is Administrator');
$translator->AddTranslation($lang, 'Core.UserForm.Password', 'New Password (leave empty to not reset it)');
$translator->AddTranslation($lang, 'Core.UserForm.PasswordRepeat', 'Repeat New Password');
$translator->AddTranslation($lang, 'Core.UserForm.Name.Validation.Required.Missing', 'Login name required');
$translator->AddTranslation($lang, 'Core.UserForm.Name.Validation.DatabaseCount.TooMuch', 'This name is already in use');
$translator->AddTranslation($lang, 'Core.UserForm.EMail.Validation.Required.Missing', 'Insert e-mail address');
$translator->AddTranslation($lang, 'Core.UserForm.EMail.Validation.PhpFilter.InvalidEmail', 'This is not a valid e-mail address');
$translator->AddTranslation($lang, 'Core.UserForm.Password.Validation.StringLength.TooShort_{0}', 'New Password must contain at least {0} characters');
$translator->AddTranslation($lang, 'Core.UserForm.PasswordRepeat.Validation.Required.Missing', 'Repeat the new password');
$translator->AddTranslation($lang, 'Core.UserForm.PasswordRepeat.Validation.CompareCheck.EqualsNot_{0}', 'The passwords are not identical');
$translator->AddTranslation($lang, 'Core.UserForm.Submit', 'Save');
//module (type) form

$translator->AddTranslation($lang, 'Core.ModuleForm.Title', 'Module Type');
$translator->AddTranslation($lang, 'Core.ModuleForm.Description', 'Select which type of element you like to add to the contents.');
$translator->AddTranslation($lang, 'Core.ModuleForm.Legend', 'Select Module Type');
$translator->AddTranslation($lang, 'Core.ModuleForm.Module', 'Type');
$translator->AddTranslation($lang, 'Core.ModuleForm.Module.Validation.Required.Missing', 'Please choose the module type for insertion');
$translator->AddTranslation($lang, 'Core.ModuleForm.Submit', 'Continue');

//navigation titles
$translator->AddTranslation($lang, 'Core.Overview.NavTitle', 'Overview');
$translator->AddTranslation($lang, 'Core.SiteList.NavTitle', 'Websites');
$translator->AddTranslation($lang, 'Core.LayoutList.NavTitle', 'Layouts');
$translator->AddTranslation($lang, 'Core.ContainerList.NavTitle', 'Element Containers');
$translator->AddTranslation($lang, 'Core.UsergroupList.NavTitle', 'User Groups');
$translator->AddTranslation($lang, 'Core.UserList.NavTitle', 'Users');
$translator->AddTranslation($lang, 'Core.NavTitle', 'Main Features');

//container rights
$translator->AddTranslation($lang, 'Core.ContainerRights.Edit', 'Edit Container');
$translator->AddTranslation($lang, 'Core.ContainerRights.Remove', 'Remove Container');


//site rights
$translator->AddTranslation($lang, 'Core.SiteRights.Edit', 'Edit website');
$translator->AddTranslation($lang, 'Core.SiteRights.Remove', 'Remove website');

//page rights
$translator->AddTranslation($lang, 'Core.PageRights.Move', 'Move pages');
$translator->AddTranslation($lang, 'Core.PageRights.Edit', 'Edit pages');
$translator->AddTranslation($lang, 'Core.PageRights.Remove', 'Delete pages');
$translator->AddTranslation($lang, 'Core.PageRights.CreateIn', 'Create pages');

//content rights
$translator->AddTranslation($lang, 'Core.ContentRights.Move', 'Move contents');
$translator->AddTranslation($lang, 'Core.ContentRights.Edit', 'Edit contents');
$translator->AddTranslation($lang, 'Core.ContentRights.Remove', 'Delete contents');
$translator->AddTranslation($lang, 'Core.ContentRights.CreateIn', 'Create contents');

//layout rights
$translator->AddTranslation($lang, 'Core.LayoutRights.Edit', 'Edit layout');
$translator->AddTranslation($lang, 'Core.LayoutRights.Remove', 'Delete layout');

//common content form fields
$translator->AddTranslation($lang, 'Core.ContentForm.CssID', 'HTML ID');
$translator->AddTranslation($lang, 'Core.ContentForm.CssClass', 'CSS Class(es)');
$translator->AddTranslation($lang, 'Core.ContentForm.UserGroup', 'Owner Group & Rights');
$translator->AddTranslation($lang, 'Core.ContentForm.UserGroup.Inherit_{0}', '-- Inherit ({0}) --');
$translator->AddTranslation($lang, 'Core.ContentForm.UserGroup.Inherit', '-- Inherit (No group) --');
$translator->AddTranslation($lang, 'Core.ContentForm.Template', 'Template');
$translator->AddTranslation($lang, 'Core.ContentForm.Template.Default', 'Use default');
$translator->AddTranslation($lang, 'Core.ContentForm.CacheLifetime', 'Cache Lifetime (in seconds)');
$translator->AddTranslation($lang, 'Core.ContentForm.CacheLifetime.Validation.Integer.HasNonDigits', 'Enter an integer value for the cache duration in seconds');


//template list
$translator->AddTranslation($lang, 'Core.TemplateList.NavTitle', 'Templates');
$translator->AddTranslation($lang, 'Core.TemplateList.Title', 'Templates');
$translator->AddTranslation($lang, 'Core.TemplateList.Description', 'Edit the templates in this list to match your needs. The templates are sorted by bundles and module names');
$translator->AddTranslation($lang, 'Core.TemplateList.Create', 'Create new template for this module');
$translator->AddTranslation($lang, 'Core.TemplateList.Edit', 'Edit this template');
$translator->AddTranslation($lang, 'Core.TemplateList.Delete', 'Delete this template');

//template form
$translator->AddTranslation($lang, 'Core.TemplateForm.Title', 'Edit Template');
$translator->AddTranslation($lang, 'Core.TemplateForm.Description', 'Edit the name and the contents of the template, here.');
$translator->AddTranslation($lang, 'Core.TemplateForm.Legend', 'Template Settings');
$translator->AddTranslation($lang, 'Core.TemplateForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.TemplateForm.Contents', 'Contents');
$translator->AddTranslation($lang, 'Core.TemplateForm.Submit', 'Save');

$translator->AddTranslation($lang, 'Core.TemplateForm.Name.Validation.Required.Missing', 'Please enter a template name');
$translator->AddTranslation($lang, 'Core.TemplateForm.Name.Validation.FileExists.InFolder_{0}', 'This template name is already in use');
$translator->AddTranslation($lang, 'Core.TemplateForm.Name.Validation.RegExp.NoMatch', 'Use only characters allowed in file names on any platform');

//common button texts
$translator->AddTranslation($lang, 'Core.ButtonText.Back', 'Back');

//exception error texts
$translator->AddTranslation($lang, 'Core.Replacer.Error.PageNotFound.ID_{0}', 'Error replacing page variable: page {0} not found');
$translator->AddTranslation($lang, 'Core.Replacer.Error.SiteNotFound.ID_{0}', 'Error replacing page variable: website {0} not found');
$translator->AddTranslation($lang, 'Core.Replacer.Error.FilterNotFound.Name_{0}', 'Error replacing page variable: filter function {0} doesn´t exist');
$translator->AddTranslation($lang, 'Core.Form.Error.ElementNotFound.Name_{0}', "Attempt to render form element '{0}', which is not part of this form");
$translator->AddTranslation($lang, 'Core.CacheKey.Error.NotAlphaNumeric', 'Cache keys must be alphanumeric');
$translator->AddTranslation($lang, 'Core.FieldColumnizer.Error.MaxColumns.MustDivide12', 'Maximum column amount must divide 12');


//overview texts
$translator->AddTranslation($lang, 'Core.Overview.Title', 'Welcome To Phine CMS!');
$translator->AddTranslation($lang, 'Core.Overview.Description', 'The available Phine modules are explained here with a short description. You can choose your desired module by clicking the related headline. Moreover, you can adjust the global settings, here.');
$translator->AddTranslation($lang, 'Core.Overview.Settings.LinkText', 'Global Settings');
$translator->AddTranslation($lang, 'Core.OverviewTitle', 'Core Features');
$translator->AddTranslation($lang, 'Core.OverviewDescription', 'The core features contain all the base functionality you need to manage your webistes using the Phine CMS');
$translator->AddTranslation($lang, 'Core.ContainerList.OverviewTitle', 'Containers');
$translator->AddTranslation($lang, 'Core.ContainerList.OverviewDescription', 'Containers are units where you can store contents to use them in various places');
$translator->AddTranslation($lang, 'Core.LayoutList.OverviewTitle', 'Layouts');
$translator->AddTranslation($lang, 'Core.LayoutList.OverviewDescription', 'Layouts define the content structure of your web pages. Using an HTML template, you can mix-in contents in customizable layout areas.');
$translator->AddTranslation($lang, 'Core.SiteList.OverviewTitle', 'Websites');
$translator->AddTranslation($lang, 'Core.SiteList.OverviewDescription', 'You can manage multiple websites with the Phine CMS. Create a new site or follow the links in the list to manage them.');
$translator->AddTranslation($lang, 'Core.TemplateList.OverviewTitle', 'Templates');
$translator->AddTranslation($lang, 'Core.TemplateList.OverviewDescription', 'Some content modules allow you to customize their HTML output. This is done by using customized templates.');
$translator->AddTranslation($lang, 'Core.UsergroupList.OverviewTitle', 'Backend User Groups');
$translator->AddTranslation($lang, 'Core.UsergroupList.OverviewDescription', 'As for Phine being a fully featured web editorial system, access rights can be defined in details using backend user groups.');
$translator->AddTranslation($lang, 'Core.UserList.OverviewTitle', 'User List');
$translator->AddTranslation($lang, 'Core.UserList.OverviewDescription', 'Phine is a multi-user CMS. There can be added an arbitrary number of backend users.');
$translator->AddTranslation($lang, 'Core.MembergroupList.OverviewTitle', 'Frontend User Groups');
$translator->AddTranslation($lang, 'Core.MembergroupList.OverviewDescription', 'Frontend users (members) can be assigned to groups. Pages and their elements can then be restricted to specific member groups.');
$translator->AddTranslation($lang, 'Core.MemberList.OverviewTitle', 'Frontend Users');
$translator->AddTranslation($lang, 'Core.MemberList.OverviewDescription', 'The list of site members with special access rights can be found here. As an administrator, you can even change their properties.');

//ajax page / page url selection
$translator->AddTranslation($lang, 'Core.PageUrlSelector.NoPage', '- No URL selected -');
$translator->AddTranslation($lang, 'Core.PageUrlSelector.OpenModal', 'Assign new URL');
$translator->AddTranslation($lang, 'Core.PageSelector.NoPage', '- No page selected');
$translator->AddTranslation($lang, 'Core.PageSelector.OpenModal', 'Assign new page');

$translator->AddTranslation($lang, 'Core.AjaxSelectPage.NoPage', '- Unselect URL -');
$translator->AddTranslation($lang, 'Core.AjaxSelectPage.Submit', 'Continue');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.Legend', 'Parameters & Fragment ID');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.OptionalParameters', 'Optional Parameters');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.OptionalParameters.Placeholder', 'One assignment per line; Format: param=value');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.Fragment', 'URL-Fragment');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.Fragment.Placeholder', 'HTML element ID within the target page; optional');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.Submit', 'Save');

$translator->AddTranslation($lang, 'Core.AjaxPageParams.Param.Validation.Required.Missing', 'Your selected page requires a value for this parameter');

//member list
$translator->AddTranslation($lang, 'Core.MemberList.NavTitle', 'Frontend Users');
$translator->AddTranslation($lang, 'Core.MemberList.Title', 'Frontend User List');
$translator->AddTranslation($lang, 'Core.MemberList.Description.Amount_{0}', 'The registered frontend users are listed, here. There are currently {0} members for this installation.');
$translator->AddTranslation($lang, 'Core.MemberList.New', 'Create new member');
$translator->AddTranslation($lang, 'Core.MemberList.Name', 'Login Name');
$translator->AddTranslation($lang, 'Core.MemberList.Edit', 'Edit user');
$translator->AddTranslation($lang, 'Core.MemberList.Delete', 'Delete user');

//member form
$translator->AddTranslation($lang, 'Core.MemberForm.Title', 'Edit Member');

$translator->AddTranslation($lang, 'Core.MemberForm.Description', 'Asjust the settings of the frontend user in this form.');
$translator->AddTranslation($lang, 'Core.MemberForm.Legend', 'Member settings');
$translator->AddTranslation($lang, 'Core.MemberForm.EMail', 'E-Mail');
$translator->AddTranslation($lang, 'Core.MemberForm.Name', 'Login Name');
$translator->AddTranslation($lang, 'Core.MemberForm.Password', 'Password');
$translator->AddTranslation($lang, 'Core.MemberForm.Password.Placeholder', 'Leave empty to keep as is');
$translator->AddTranslation($lang, 'Core.MemberForm.MemberGroup', 'Assign member groups');
$translator->AddTranslation($lang, 'Core.MemberForm.Submit', 'Save');

$translator->AddTranslation($lang, 'Core.MemberForm.EMail.Validation.Required.Missing', 'E-mail address is required');
$translator->AddTranslation($lang, 'Core.MemberForm.Name.Validation.Required.Missing', 'Login Name must not be empty');
$translator->AddTranslation($lang, 'Core.MemberForm.Password.Validation.Required.Missing', 'Password must be set for new users');
$translator->AddTranslation($lang, 'Core.MemberForm.Password.Validation.StringLength.TooShort_{0}', 'Required password length is at least {0} characters');

//member group list
$translator->AddTranslation($lang, 'Core.MembergroupList.NavTitle', 'Frontend Groups');
$translator->AddTranslation($lang, 'Core.MembergroupList.Title', 'Member Groups');
$translator->AddTranslation($lang, 'Core.MembergroupList.Description.Amount_{0}', 'Use frontend groups to control content access for registered users. Currently {0} user groups exist.');

$translator->AddTranslation($lang, 'Core.MembergroupList.New', 'Create New Group');
$translator->AddTranslation($lang, 'Core.MembergroupList.Name', 'Group Name');
$translator->AddTranslation($lang, 'Core.MembergroupList.Edit', 'Edit group');
$translator->AddTranslation($lang, 'Core.MembergroupList.Delete', 'Delete group');

// member group form
$translator->AddTranslation($lang, 'Core.MembergroupForm.Title', 'Edit Member Group');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Description', 'Definte the properties of the frontend user group, here.');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Legend', 'Group Settings');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Submit', 'Save');

$translator->AddTranslation($lang, 'Core.MembergroupForm.Name.Validation.Required.Missing', 'Group name is missing');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Name.Validation.DatabaseCount.TooMuch', 'There is another group using this name');

// settings form
$translator->AddTranslation($lang, 'Core.SettingsForm.Title', 'Global Settings');
$translator->AddTranslation($lang, 'Core.SettingsForm.Description', 'Gather global settings for mailing and logging using this form.');
$translator->AddTranslation($lang, 'Core.SettingsForm.Legend', 'Log & Mail Base settings');
$translator->AddTranslation($lang, 'Core.SettingsForm.LogLifetime', 'Lifetime Log Items in days');
$translator->AddTranslation($lang, 'Core.SettingsForm.LogLifetime.Validation.Integer.ExceedsMax_{0}', 'The maximum lifetime is {0} days');
$translator->AddTranslation($lang, 'Core.SettingsForm.ChangeRequestLifetime', 'Lifetime user links (f.e. new password) in days');

$translator->AddTranslation($lang, 'Core.SettingsForm.MailFromEMail', 'Mail Sender E-Mail');
$translator->AddTranslation($lang, 'Core.SettingsForm.MailFromEMail.Validation.PhpFilter.InvalidEmail', 'Invalid e-mail address');
$translator->AddTranslation($lang, 'Core.SettingsForm.MailFromName', 'Mail Sender Name');
$translator->AddTranslation($lang, 'Core.SettingsForm.Smtp.Legend', 'SMTP Setup');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpHost', 'Host');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpPort', 'Port');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpPort.Validation.Integer.HasNonDigits', 'Port must be numeric');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpPort.Validation.Integer.ExceedsMax_{0}', 'Highest port is 65535');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpSecurity', 'Security');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpSecurity.None', 'None');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpSecurity.Ssl', 'SSL');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpSecurity.Tls', 'TLS');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpUser', 'Username');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpPassword', 'Password');
$translator->AddTranslation($lang, 'Core.SettingsForm.Submit', 'Save');

// member groups
$translator->AddTranslation($lang, 'Core.PageForm.GuestsOnly', 'Show Guests, Only');
$translator->AddTranslation($lang, 'Core.ContentForm.GuestsOnly', 'Show Guests, Only');
$translator->AddTranslation($lang, 'Core.PageForm.MemberGroup', 'Restrict Access to Frontend Groups');
$translator->AddTranslation($lang, 'Core.ContentForm.MemberGroup', 'Restrict Access to Frontend Groups');
$translator->AddTranslation($lang, 'Core.ContentForm.Publish', 'Publish');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishFromDate', 'Visible from');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishFromHour', 'Hrs.');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishFromMinute', 'Min.');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishToDate', 'Visible to');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishToHour', 'Hrs.');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishToMinute', 'Min.');
$translator->AddTranslation($lang, 'Core.ContentForm.Wording.Placeholder', '< use default >');

$translator->AddTranslation($lang, 'Core.ContentForm.Legend.Access', 'Access Rights');
$translator->AddTranslation($lang, 'Core.ContentForm.Legend.Wordings', 'Texts');