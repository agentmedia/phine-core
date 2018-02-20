ALTER TABLE `pc_core_area`
  ADD CONSTRAINT `pc_core_area_ibfk_1` FOREIGN KEY (`Layout`) REFERENCES `pc_core_layout` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_area_ibfk_2` FOREIGN KEY (`Previous`) REFERENCES `pc_core_area` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pc_core_backend_container_rights`
  ADD CONSTRAINT `pc_core_backend_container_rights_ibfk_1` FOREIGN KEY (`ContentRights`) REFERENCES `pc_core_backend_content_rights` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_backend_layout_rights`
  ADD CONSTRAINT `pc_core_backend_layout_rights_ibfk_1` FOREIGN KEY (`ContentRights`) REFERENCES `pc_core_backend_content_rights` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_backend_page_rights`
  ADD CONSTRAINT `pc_core_backend_page_rights_ibfk_1` FOREIGN KEY (`ContentRights`) REFERENCES `pc_core_backend_content_rights` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_backend_site_rights`
  ADD CONSTRAINT `pc_core_backend_site_rights_ibfk_1` FOREIGN KEY (`PageRights`) REFERENCES `pc_core_backend_page_rights` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_container`
  ADD CONSTRAINT `pc_core_container_ibfk_1` FOREIGN KEY (`User`) REFERENCES `pc_core_user` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_container_ibfk_2` FOREIGN KEY (`UserRights`) REFERENCES `pc_core_backend_container_rights` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_container_ibfk_3` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_container_ibfk_4` FOREIGN KEY (`UserGroupRights`) REFERENCES `pc_core_backend_container_rights` (`ID`) ON DELETE SET NULL;

ALTER TABLE `pc_core_container_content`
  ADD CONSTRAINT `pc_core_container_content_ibfk_1` FOREIGN KEY (`Container`) REFERENCES `pc_core_container` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_container_content_ibfk_2` FOREIGN KEY (`Content`) REFERENCES `pc_core_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_container_content_ibfk_3` FOREIGN KEY (`Parent`) REFERENCES `pc_core_container_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_container_content_ibfk_4` FOREIGN KEY (`Previous`) REFERENCES `pc_core_container_content` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pc_core_content`
  ADD CONSTRAINT `pc_core_content_ibfk_1` FOREIGN KEY (`LayoutContent`) REFERENCES `pc_core_layout_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_content_ibfk_2` FOREIGN KEY (`PageContent`) REFERENCES `pc_core_page_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_content_ibfk_3` FOREIGN KEY (`ContainerContent`) REFERENCES `pc_core_container_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_content_ibfk_4` FOREIGN KEY (`User`) REFERENCES `pc_core_user` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_content_ibfk_5` FOREIGN KEY (`UserRights`) REFERENCES `pc_core_backend_content_rights` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_content_ibfk_6` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_content_ibfk_7` FOREIGN KEY (`UserGroupRights`) REFERENCES `pc_core_backend_content_rights` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pc_core_language`
  ADD CONSTRAINT `pc_core_language_ibfk_1` FOREIGN KEY (`Parent`) REFERENCES `pc_core_language` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pc_core_layout`
  ADD CONSTRAINT `pc_core_layout_ibfk_1` FOREIGN KEY (`User`) REFERENCES `pc_core_user` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_layout_ibfk_2` FOREIGN KEY (`UserRights`) REFERENCES `pc_core_backend_layout_rights` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_layout_ibfk_3` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_layout_ibfk_4` FOREIGN KEY (`UserGroupRights`) REFERENCES `pc_core_backend_layout_rights` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pc_core_layout_content`
  ADD CONSTRAINT `pc_core_layout_content_ibfk_1` FOREIGN KEY (`Area`) REFERENCES `pc_core_area` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_layout_content_ibfk_2` FOREIGN KEY (`Previous`) REFERENCES `pc_core_layout_content` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_layout_content_ibfk_3` FOREIGN KEY (`Content`) REFERENCES `pc_core_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_layout_content_ibfk_4` FOREIGN KEY (`Parent`) REFERENCES `pc_core_layout_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_module_lock`
  ADD CONSTRAINT `pc_core_module_lock_ibfk_1` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_page`
  ADD CONSTRAINT `pc_core_page_ibfk_1` FOREIGN KEY (`Site`) REFERENCES `pc_core_site` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_ibfk_2` FOREIGN KEY (`Parent`) REFERENCES `pc_core_page` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_ibfk_3` FOREIGN KEY (`Previous`) REFERENCES `pc_core_page` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_ibfk_4` FOREIGN KEY (`Layout`) REFERENCES `pc_core_layout` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_ibfk_5` FOREIGN KEY (`User`) REFERENCES `pc_core_user` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_ibfk_6` FOREIGN KEY (`UserRights`) REFERENCES `pc_core_backend_page_rights` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_ibfk_7` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_ibfk_8` FOREIGN KEY (`UserGroupRights`) REFERENCES `pc_core_backend_page_rights` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pc_core_page_content`
  ADD CONSTRAINT `pc_core_page_content_ibfk_1` FOREIGN KEY (`Page`) REFERENCES `pc_core_page` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_content_ibfk_2` FOREIGN KEY (`Area`) REFERENCES `pc_core_area` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_content_ibfk_3` FOREIGN KEY (`Previous`) REFERENCES `pc_core_page_content` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_content_ibfk_4` FOREIGN KEY (`Parent`) REFERENCES `pc_core_page_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_content_ibfk_5` FOREIGN KEY (`Content`) REFERENCES `pc_core_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_site`
  ADD CONSTRAINT `pc_core_site_ibfk_1` FOREIGN KEY (`User`) REFERENCES `pc_core_user` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_site_ibfk_2` FOREIGN KEY (`UserRights`) REFERENCES `pc_core_backend_site_rights` (`ID`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `pc_core_site_ibfk_3` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_site_ibfk_4` FOREIGN KEY (`UserGroupRights`) REFERENCES `pc_core_backend_site_rights` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pc_core_user`
  ADD CONSTRAINT `pc_core_user_ibfk_1` FOREIGN KEY (`Language`) REFERENCES `pc_core_language` (`ID`) ON UPDATE CASCADE;

ALTER TABLE `pc_core_usergroup_site`
  ADD CONSTRAINT `pc_core_usergroup_site_ibfk_1` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_usergroup_site_ibfk_2` FOREIGN KEY (`Site`) REFERENCES `pc_core_site` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_user_usergroup`
  ADD CONSTRAINT `pc_core_user_usergroup_ibfk_1` FOREIGN KEY (`User`) REFERENCES `pc_core_user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_user_usergroup_ibfk_2` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_page_url`
  ADD CONSTRAINT `pc_core_page_url_ibfk_1` FOREIGN KEY (`Page`) REFERENCES `pc_core_page` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_page_url_parameter`
  ADD CONSTRAINT `pc_core_page_url_parameter_ibfk_2` FOREIGN KEY (`Previous`) REFERENCES `pc_core_page_url_parameter` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_url_parameter_ibfk_1` FOREIGN KEY (`PageUrl`) REFERENCES `pc_core_page_url` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_content_membergroup`
  ADD CONSTRAINT `pc_core_content_membergroup_ibfk_2` FOREIGN KEY (`MemberGroup`) REFERENCES `pc_core_membergroup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_content_membergroup_ibfk_1` FOREIGN KEY (`Content`) REFERENCES `pc_core_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_member_membergroup`
  ADD CONSTRAINT `pc_core_member_membergroup_ibfk_1` FOREIGN KEY (`Member`) REFERENCES `pc_core_member` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_member_membergroup_ibfk_2` FOREIGN KEY (`MemberGroup`) REFERENCES `pc_core_membergroup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_page_membergroup`
  ADD CONSTRAINT `pc_core_page_membergroup_ibfk_2` FOREIGN KEY (`MemberGroup`) REFERENCES `pc_core_membergroup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_page_membergroup_ibfk_1` FOREIGN KEY (`Page`) REFERENCES `pc_core_page` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_site` ADD FOREIGN KEY (`Language`) REFERENCES `pc_core_language`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_content_wording` ADD FOREIGN KEY (`Content`) REFERENCES `pc_core_content`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_item`
  ADD CONSTRAINT `pc_core_log_item_ibfk_1` FOREIGN KEY (`User`) REFERENCES `pc_core_user` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_user`
  ADD CONSTRAINT `pc_core_log_user_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_user_ibfk_1` FOREIGN KEY (`User`) REFERENCES `pc_core_user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_page`
  ADD CONSTRAINT `pc_core_log_page_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_page_ibfk_1` FOREIGN KEY (`Page`) REFERENCES `pc_core_page` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_site`
  ADD CONSTRAINT `pc_core_log_site_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_site_ibfk_1` FOREIGN KEY (`Site`) REFERENCES `pc_core_site` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_layout`
  ADD CONSTRAINT `pc_core_log_layout_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_layout_ibfk_1` FOREIGN KEY (`Layout`) REFERENCES `pc_core_layout` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_area`
  ADD CONSTRAINT `pc_core_log_area_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_area_ibfk_1` FOREIGN KEY (`Area`) REFERENCES `pc_core_area` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_content`
  ADD CONSTRAINT `pc_core_log_content_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_content_ibfk_1` FOREIGN KEY (`Content`) REFERENCES `pc_core_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_member`
  ADD CONSTRAINT `pc_core_log_member_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_member_ibfk_1` FOREIGN KEY (`Member`) REFERENCES `pc_core_member` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_membergroup`
  ADD CONSTRAINT `pc_core_log_membergroup_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_membergroup_ibfk_1` FOREIGN KEY (`MemberGroup`) REFERENCES `pc_core_membergroup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_template`
  ADD CONSTRAINT `pc_core_log_template_ibfk_1` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_usergroup`
  ADD CONSTRAINT `pc_core_log_usergroup_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_usergroup_ibfk_1` FOREIGN KEY (`UserGroup`) REFERENCES `pc_core_usergroup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_log_container`
  ADD CONSTRAINT `pc_core_log_container_ibfk_2` FOREIGN KEY (`LogItem`) REFERENCES `pc_core_log_item` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pc_core_log_container_ibfk_1` FOREIGN KEY (`Container`) REFERENCES `pc_core_container` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pc_core_page`
  ADD CONSTRAINT `pc_core_page_ibfk_9` FOREIGN KEY (`RedirectTarget`) REFERENCES `pc_core_page_url` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;

-- New in 1.2.3 --
ALTER TABLE `pc_core_member_change_request`
  ADD CONSTRAINT `pc_core_member_change_request_ibfk_1` FOREIGN KEY (`Member`) REFERENCES `pc_core_member` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
