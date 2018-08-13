<?php

use Phine\Framework\Localization\PhpTranslator;
$translator = PhpTranslator::Singleton();
$lang = 'de';

//date & numeric formats
$translator->AddTranslation($lang, 'Core.DateFormat', 'd.m.Y');
$translator->AddTranslation($lang, 'Core.DecimalSeparator', ',');
$translator->AddTranslation($lang, 'Core.ThousandsSeparator', '.');
//login
$translator->AddTranslation($lang, 'Core.Login.Legend', 'Login');
$translator->AddTranslation($lang, 'Core.Login.Name', 'Nutzername');
$translator->AddTranslation($lang, 'Core.Login.Password', 'Passwort');
$translator->AddTranslation($lang, 'Core.Login.Submit', 'Anmelden');
$translator->AddTranslation($lang, 'Core.Login.Failed', 'Sie haben keine gültigen Anmeldedaten eingegeben.');

//common texts
$translator->AddTranslation($lang, 'Core.PleaseSelect', '-- Bitte wählen --');

//common enums
//-- sitemap change frequency

$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Always', 'Jederzeit');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Hourly', 'Stündlich');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Daily', 'Täglich');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Weekly', 'Wöchentlich');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Monthly', 'Monatlich');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Yearly', 'Jährlich');
$translator->AddTranslation($lang, 'Core.Sitemap.ChangeFrequency.Never', 'Niemals');


// deletion modal
$translator->AddTranslation($lang, 'Core.ModalDelete.Title', 'Löschen bestätigen');
$translator->AddTranslation($lang, 'Core.ModalDelete.Description.Name_{0}', "Möchten Sie den Eintrag '{0}' inklusive aller Abhängigkeiten wirklich löschen?");
$translator->AddTranslation($lang, 'Core.ModalDelete.Cancel', 'Nein, abbrechen!');
$translator->AddTranslation($lang, 'Core.ModalDelete.Confirm', 'Ja, löschen!');


//site list
$translator->AddTranslation($lang, 'Core.SiteList.Title', 'Websites');
$translator->AddTranslation($lang, 'Core.SiteList.Description.Amount_{0}', 'Es befinden sich {0} Websites unter der Kontrolle dieser Phine-Installation.<br />Klicken Sie auf das jeweilige <strong>Seiten-Symbol</strong>, um Seiten und Inhalte des Webauftritts anzupassen.');
$translator->AddTranslation($lang, 'Core.SiteList.New', 'Neue Website erstellen');
$translator->AddTranslation($lang, 'Core.SiteList.Name', 'Website-Name');
$translator->AddTranslation($lang, 'Core.SiteList.PageTree', 'Seitenstruktur');
$translator->AddTranslation($lang, 'Core.SiteList.Edit', 'Website-Einstellungen');
$translator->AddTranslation($lang, 'Core.SiteList.Delete', 'Diese Website löschen');


//site form
$translator->AddTranslation($lang, 'Core.SiteForm.Title', 'Website Bearbeiten');

$translator->AddTranslation($lang, 'Core.SiteForm.Description', 'Sie können hier  die Basis-Einstellungen der Website verändern.');
$translator->AddTranslation($lang, 'Core.SiteForm.Legend', 'Website-Einstellungen');
$translator->AddTranslation($lang, 'Core.SiteForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.SiteForm.Url', 'URL');
$translator->AddTranslation($lang, 'Core.SiteForm.NoGroup', '-- Keine Usergruppe --');
$translator->AddTranslation($lang, 'Core.SiteForm.UserGroup', 'Eigentümer-Gruppe & Berechtigungen');
$translator->AddTranslation($lang, 'Core.SiteForm.Submit', 'Speichern');
$translator->AddTranslation($lang, 'Core.SiteForm.Name.Validation.Required.Missing', 'Bitte eindeutigen Seitennamen eingeben');
$translator->AddTranslation($lang, 'Core.SiteForm.Url.Validation.Required.Missing', 'Website URL definieren');
$translator->AddTranslation($lang, 'Core.SiteForm.Name.Validation.DatabaseCount.TooMuch', 'Dieser Name ist bereits in Verwendung');
$translator->AddTranslation($lang, 'Core.SiteForm.Url.Validation.PhpFilter.InvalidUrl', 'Dies ist keine gültige URL');
$translator->AddTranslation($lang, 'Core.SiteForm.Language', 'Sprache');
$translator->AddTranslation($lang, 'Core.SiteForm.Language.Validation.Required.Missing', 'Bitte Sprache auswählen');

$translator->AddTranslation($lang, 'Core.SiteForm.Access.Legend', 'Zugriffsberechtigungen');
$translator->AddTranslation($lang, 'Core.SiteForm.Sitemap.Legend', 'Sitemap-Einstellungen');
$translator->AddTranslation($lang, 'Core.SiteForm.SitemapActive', 'Sitemap generieren');
$translator->AddTranslation($lang, 'Core.SiteForm.SitemapCacheLifetime', 'Sitemap Lebensdauer (in Sekunden)');
$translator->AddTranslation($lang, 'Core.SiteForm.SitemapCacheLifetime.Validation.Integer.HasNonDigits', 'Dies ist keine gültige Zahl');

//layout list
$translator->AddTranslation($lang, 'Core.LayoutList.Title', 'Layouts');
$translator->AddTranslation($lang, 'Core.LayoutList.Description.Amount_{0}', 'Es sind derzeit {0} Layouts angelegt.');
$translator->AddTranslation($lang, 'Core.LayoutList.Name', 'Layout-Name');
$translator->AddTranslation($lang, 'Core.LayoutList.EditArea', 'Layout-Bereiche anzeigen');
$translator->AddTranslation($lang, 'Core.LayoutList.Edit', 'Layout-Einstellungen');
$translator->AddTranslation($lang, 'Core.LayoutList.EditTemplate', 'Layout-Template bearbeiten');

$translator->AddTranslation($lang, 'Core.LayoutList.New', 'Neues Layout erstellen');
$translator->AddTranslation($lang, 'Core.LayoutList.Delete', 'Layout löschen');

//layout form
$translator->AddTranslation($lang, 'Core.LayoutForm.Title', 'Layout Bearbeiten');
$translator->AddTranslation($lang, 'Core.LayoutForm.Description', 'Ändern Sie hier die grundlegenden Eigenschaften des Layouts.');

$translator->AddTranslation($lang, 'Core.LayoutForm.Legend', 'Einstellugen');
$translator->AddTranslation($lang, 'Core.LayoutForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.LayoutForm.Areas', 'Bereiche');
$translator->AddTranslation($lang, 'Core.LayoutForm.Areas.Placeholder', 'Mit Komma getrennt, z.B. Top, Main, Left, Right');
$translator->AddTranslation($lang, 'Core.LayoutForm.UserGroup', 'Eigentümer-Gruppe & Berechtigungen');

$translator->AddTranslation($lang, 'Core.LayoutForm.NoGroup', '-- Keine Gruppe --');
$translator->AddTranslation($lang, 'Core.LayoutForm.Submit', 'Speichern & Weiter');
$translator->AddTranslation($lang, 'Core.LayoutForm.Name.Validation.Required.Missing', 'Eindeutigen Namen vergeben');
$translator->AddTranslation($lang, 'Core.LayoutForm.Areas.Validation.Required.Missing', 'Mindestens einen Bereich eingeben');
$translator->AddTranslation($lang, 'Core.LayoutForm.Name.Validation.DatabaseCount.TooMuch', 'Dieser Name ist bereits in Verwendung');

//layout template form
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Title', 'Layout-Template bearbeiten');
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Description', 'Bearbeiten Sie hier das Laout-Template und verschieben Sie die Bereiche an die dafür vorgesehenen Positionen im HTML-Code.');
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Legend', 'Layout-Template');
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Contents', 'Template-Code');
$translator->AddTranslation($lang, 'Core.LayoutTemplateForm.Submit', 'Speichern');

// area list
$translator->AddTranslation($lang, 'Core.AreaList.Title', 'Layout-Bereiche');
$translator->AddTranslation($lang, 'Core.AreaList.Description', 'Die Layout-Bereiche können hier bearbeitet und verschoben werden.');
$translator->AddTranslation($lang, 'Core.AreaList.CreateStartArea', 'Layout-Bereich oben erstellen');
$translator->AddTranslation($lang, 'Core.AreaList.CreateAfter', 'Neuer Bereich hiernach');
$translator->AddTranslation($lang, 'Core.AreaList.Cut', 'Ausschneiden zum verschieben');
$translator->AddTranslation($lang, 'Core.AreaList.InsertAfter', 'Hiernach einfügen');
$translator->AddTranslation($lang, 'Core.AreaList.InsertIn', 'Als Unterelement hier einfügen');
$translator->AddTranslation($lang, 'Core.AreaList.Back', 'Zurück zur Layout-Liste');
$translator->AddTranslation($lang, 'Core.AreaList.CancelCut', 'Cancel moving area');
$translator->AddTranslation($lang, 'Core.AreaList.Edit', 'Namen und Sperrung bearbeiten');

// area form
$translator->AddTranslation($lang, 'Core.AreaForm.Title', 'Bereich bearbeiten');
$translator->AddTranslation($lang, 'Core.AreaForm.Description', 'Bereichsnamen und Sperrung können Sie hier festlegen.');
$translator->AddTranslation($lang, 'Core.AreaForm.Legend', 'Bereichseinstellungen');
$translator->AddTranslation($lang, 'Core.AreaForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.AreaForm.Name.Validation.Required.Missing', 'Name des Bereiches eingeben');
$translator->AddTranslation($lang, 'Core.AreaForm.Name.Validation.DatabaseCount.TooMuch', 'Bereichsname wird bereits verwendet');
$translator->AddTranslation($lang, 'Core.AreaForm.Locked', 'Für jeweilige Nutzergruppe sperren');
$translator->AddTranslation($lang, 'Core.AreaForm.Submit', 'Speichern');

// container list
$translator->AddTranslation($lang, 'Core.ContainerList.Title', 'Container-Liste');
$translator->AddTranslation($lang, 'Core.ContainerList.Description.Amount_{0}', 'In Containern könnnen Sie Inhalte einfügen, die Sie an mehreren Stellen Ihrer Webseiten einfügen können. Derzeit sind {0} Container vorhanden.');
$translator->AddTranslation($lang, 'Core.ContainerList.New', 'Neuen Container erstellen');
$translator->AddTranslation($lang, 'Core.ContainerList.Name', 'Container-Name');
$translator->AddTranslation($lang, 'Core.ContainerList.EditContents', 'Container-Inhalte bearbeiten');
$translator->AddTranslation($lang, 'Core.ContainerList.Edit', 'Container-Einstellungen bearbeiten');
$translator->AddTranslation($lang, 'Core.ContainerList.Delete', 'Container löschen');

//container form
$translator->AddTranslation($lang, 'Core.ContainerForm.Title', 'Container bearbeiten');
$translator->AddTranslation($lang, 'Core.ContainerForm.Description', 'Hier können Sie die Grundeinstellunge für den Container verändern.');
$translator->AddTranslation($lang, 'Core.ContainerForm.Legend', 'Container-Einstellungen');
$translator->AddTranslation($lang, 'Core.ContainerForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.ContainerForm.Name.Validation.Required.Missing', 'Name des Containers eingeben');
$translator->AddTranslation($lang, 'Core.ContainerForm.Name.Validation.DatabaseCount.TooMuch', 'Der Containername ist bereits in Verwendung');
$translator->AddTranslation($lang, 'Core.ContainerForm.UserGroup', 'Backend-Nutzergruppe & Rechte');
$translator->AddTranslation($lang, 'Core.ContainerForm.NoGroup', '-- Keine Gruppe --');
$translator->AddTranslation($lang, 'Core.ContainerForm.Submit', 'Speichern');

// page tree
$translator->AddTranslation($lang, 'Core.PageTree.Title', 'Seiten-Baumansicht');
$translator->AddTranslation($lang, 'Core.PageTree.Description', 'Bearbeiten Sie hier die Seiten und ihren Inhalte des gewählten Webauftritts.');
$translator->AddTranslation($lang, 'Core.PageTree.CreateStartPage', 'Neue Startseite anlegen');
$translator->AddTranslation($lang, 'Core.PageTree.CreateIn', 'Neue Seite innerhalb anlegen');
$translator->AddTranslation($lang, 'Core.PageTree.CreateAfter', 'Neue Seite unterhalb anlegen');
$translator->AddTranslation($lang, 'Core.PageTree.InsertIn', 'Kopierte Seite innerhalb dieser einfügen');
$translator->AddTranslation($lang, 'Core.PageTree.InsertAfter', 'Kopierte Seite nach dieser einfügen');
$translator->AddTranslation($lang, 'Core.PageTree.Cut', 'Ausschneiden zum Verschieben');
$translator->AddTranslation($lang, 'Core.PageTree.CancelCut', 'Seiten-Verschiebung abbrechen');
$translator->AddTranslation($lang, 'Core.PageTree.Edit', 'Seiteneinstellungen bearbeiten');
$translator->AddTranslation($lang, 'Core.PageTree.EditArea', 'Anklicken um die Layout-Bereiche zu sehen');
$translator->AddTranslation($lang, 'Core.PageTree.Delete', 'Seite löschen');

//page form
$translator->AddTranslation($lang, 'Core.PageForm.Headline', 'Seite bearbeiten');
$translator->AddTranslation($lang, 'Core.PageForm.Explanation', 'Legen Sie hier URL, Name, Zugriffsrechte und andere Parameter für die Seite fest.');
$translator->AddTranslation($lang, 'Core.PageForm.Legend', 'Seiteneinstellungen');
$translator->AddTranslation($lang, 'Core.PageForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.PageForm.Url', 'URL');
$translator->AddTranslation($lang, 'Core.PageForm.Layout', 'Layout');
$translator->AddTranslation($lang, 'Core.PageForm.Title', 'Titel');
$translator->AddTranslation($lang, 'Core.PageForm.Description', 'Meta-Description');
$translator->AddTranslation($lang, 'Core.PageForm.Keywords', 'Meta-Keywords');
$translator->AddTranslation($lang, 'Core.PageForm.MenuAccess', 'Menü-Sichtbarkeit');

$translator->AddTranslation($lang, 'Core.PageForm.Publish', 'Veröffentlichen');
$translator->AddTranslation($lang, 'Core.PageForm.PublishFromDate', 'Sichtbar ab');
$translator->AddTranslation($lang, 'Core.PageForm.PublishFromHour', 'Std.');
$translator->AddTranslation($lang, 'Core.PageForm.PublishFromMinute', 'Min.');
$translator->AddTranslation($lang, 'Core.PageForm.PublishToDate', 'Sichtbar bis');
$translator->AddTranslation($lang, 'Core.PageForm.PublishToHour', 'Std.');
$translator->AddTranslation($lang, 'Core.PageForm.PublishToMinute', 'Min.');

$translator->AddTranslation($lang, 'Core.PageForm.Name.Validation.Required.Missing', 'Seitennamen hier eintragen');
$translator->AddTranslation($lang, 'Core.PageForm.Name.Validation.DatabaseCount.TooMuch', 'Dieser Name wird bereits verwendet');
$translator->AddTranslation($lang, 'Core.PageForm.Url.Validation.Required.Missing', 'Bitte URL relativ zur Website-URL eintragen');
$translator->AddTranslation($lang, 'Core.PageForm.Layout.Validation.Required.Missing', 'Seitenlayout auswählen');

$translator->AddTranslation($lang, 'Core.PageForm.Type.Legend', 'Modus');
$translator->AddTranslation($lang, 'Core.PageForm.Type', 'Seitentyp');
$translator->AddTranslation($lang, 'Core.PageForm.Type.Normal', 'Reguläre Seite');
$translator->AddTranslation($lang, 'Core.PageForm.Type.RedirectPermanent', 'Permanente Weiterleitung (301)');
$translator->AddTranslation($lang, 'Core.PageForm.Type.RedirectTemporary', 'Temporäre Weiterleitung (302)');
$translator->AddTranslation($lang, 'Core.PageForm.Type.NotFound', '404-Seite (URL nicht gefunden)');
$translator->AddTranslation($lang, 'Core.PageForm.RedirectTarget', 'Weiterleitungsseite');
$translator->AddTranslation($lang, 'Core.PageForm.RedirectTarget.Validation.Required.Missing', 'Zielseite muss gewählt sein');

$translator->AddTranslation($lang, 'Core.PageForm.Access.Legend', 'Zugriff & Sichtbarkeit');
$translator->AddTranslation($lang, 'Core.PageForm.MenuAccess.Authorized', 'Nur für autorisierte Besucher (Standardeinstellung)');
$translator->AddTranslation($lang, 'Core.PageForm.MenuAccess.AlwaysVisible', 'Immer anzeigen');
$translator->AddTranslation($lang, 'Core.PageForm.MenuAccess.AlwaysHidden', 'Immer verstecken');

$translator->AddTranslation($lang, 'Core.PageForm.UserGroup', 'Backend-Nutzergruppe & Rechte');
$translator->AddTranslation($lang, 'Core.PageForm.UserGroup.Inherit_{0}', '-- Übergeordnete Einstellung übernehmen ({0}) --');
$translator->AddTranslation($lang, 'Core.PageForm.UserGroup.Inherit', '-- Übergeordnete Einstellung übernehmen  (Keine Nutzergruppe) --');
$translator->AddTranslation($lang, 'Core.PageForm.Sitemap.Legend', 'Sitemap-Eintrag');
$translator->AddTranslation($lang, 'Core.PageForm.SitemapRelevance', 'Relevanz (0,0 = kein Eintrag)');
$translator->AddTranslation($lang, 'Core.PageForm.SitemapChangeFrequency', 'Änderungsfrequenz');
$translator->AddTranslation($lang, 'Core.PageForm.Submit', 'Speichern');

//page content tree
$translator->AddTranslation($lang, 'Core.PageContentTree.Title', 'Seiteninhalte');
$translator->AddTranslation($lang, 'Core.PageContentTree.Description', 'Sofern Sie über die entsprechenden Berechtigungen verfügen, können Sie hier die Seiteninhalte anpassen.');
$translator->AddTranslation($lang, 'Core.PageContentTree.CreateStartElement', 'Erstes Inhaltselement hinzufügen');
$translator->AddTranslation($lang, 'Core.PageContentTree.Edit', 'Element bearbeiten');
$translator->AddTranslation($lang, 'Core.PageContentTree.Delete', 'Inhaltselement löschen');
$translator->AddTranslation($lang, 'Core.PageContentTree.Cut', 'Zum Verschieben ausschneiden');
$translator->AddTranslation($lang, 'Core.PageContentTree.CreateIn', 'Neues Element innerhalb anlegen');
$translator->AddTranslation($lang, 'Core.PageContentTree.CreateAfter', 'Neues Element unterhalb anlegen');
$translator->AddTranslation($lang, 'Core.PageContentTree.InsertIn', 'Element innerhalb einfügen');
$translator->AddTranslation($lang, 'Core.PageContentTree.InsertAfter', 'Element unterhalb einfügen');
$translator->AddTranslation($lang, 'Core.PageContentTree.CancelCut', 'Verschieben abbrechen');

//layout content tree
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Title', 'Layout-Bereichsinhalte');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Description', 'Ausreichende Rechte vorausgesetzt, können Sie hier die Inhalte des Bereichs im gewählten Layout ändern, verschieben oder  löschen.');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.CreateStartElement', 'Startelement anlegen');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Edit', 'Inhaltselement bearbeiten');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Delete', 'Inhaltselement löschen');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.Cut', 'Zum Verschieben ausschneiden');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.CreateIn', 'Neues Element innerhalb anlegen');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.CreateAfter', 'Neues Element unterhalb anlegen');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.InsertIn', 'Element innerhalb einfügen');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.InsertAfter', 'Element unterhalb einfügen');
$translator->AddTranslation($lang, 'Core.LayoutContentTree.CancelCut', 'Verschieben abbrechen');

//container content tree
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Title', 'Container-Inhalte');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Description', 'Falls Sie die nötigen Rechte haben, können Sie hier die Inhalte des Containers editieren, umsortieren oder entfernen.');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.CreateStartElement', 'Startelement anlegen');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Edit', 'Inhaltselement bearbeiten');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Delete', 'Inhaltselement löschen');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.Cut', 'Zum Verschieben ausschneiden');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.CreateIn', 'Neues Element innerhalb anlegen');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.CreateAfter', 'Neues Element unterhalb anlegen');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.InsertIn', 'Element innerhalb einfügen');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.InsertAfter', 'Element unterhalb einfügen');
$translator->AddTranslation($lang, 'Core.ContainerContentTree.CancelCut', 'Verschieben abbrechen');

//User group list
$translator->AddTranslation($lang, 'Core.UsergroupList.Title', 'Backend-Nutzergruppen');
$translator->AddTranslation($lang, 'Core.UsergroupList.Description.Amount_{0}', 'Zur Zeit existieren {0} Backend-Benutzergruppen in dieser Phine-Installation.');
$translator->AddTranslation($lang, 'Core.UsergroupList.New', 'Neue Gruppe anlege');
$translator->AddTranslation($lang, 'Core.UsergroupList.Name', 'Gruppenname');
$translator->AddTranslation($lang, 'Core.UsergroupList.Edit', 'Gruppeneigenschaften bearbeiten');
$translator->AddTranslation($lang, 'Core.UsergroupList.Delete', 'Gruppe löschen');
$translator->AddTranslation($lang, 'Core.UsergroupList.LockModules', 'Module für diese Gruppe sperren oder freigeben');

//User List
$translator->AddTranslation($lang, 'Core.UserList.Title', 'Backend-Benutzerliste');
$translator->AddTranslation($lang, 'Core.UserList.Description.Amount_{0}', 'Zur Zeit gibt es {0} Benutzer mit Zugriff auf das Backend dieser Phine-Installation.');
$translator->AddTranslation($lang, 'Core.UserList.New', 'Neuen Benutzer anlegen');
$translator->AddTranslation($lang, 'Core.UserList.Name', 'Login-Name');
$translator->AddTranslation($lang, 'Core.UserList.IsAdmin', 'Ist Administrator?');
$translator->AddTranslation($lang, 'Core.UserList.EditGroups', 'Nutzergruppen hinzufügen oder entfernen');
$translator->AddTranslation($lang, 'Core.UserList.Edit', 'Nutzer bearbeiten');
$translator->AddTranslation($lang, 'Core.UserList.Delete', 'Benutzer löschen');

//user group form
$translator->AddTranslation($lang, 'Core.UsergroupForm.Title', 'Backend-Nutzergruppe bearbeiten');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Description', 'Setzen Sie hier Name und Berechtigungen der Nutzergruppe.');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Legend', 'Gruppeneinstellungen');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Name', 'Gruppenname');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Name.Validation.Required.Missing', 'Name der Gruppe eingeben');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Name.Validation.DatabaseCount.TooMuch', 'Dieser Name wird bereits verwendet');
$translator->AddTranslation($lang, 'Core.UsergroupForm.CreateSites', 'Nutzer können Webseiten erstellen');
$translator->AddTranslation($lang, 'Core.UsergroupForm.CreateLayouts', 'Nutzer können Layouts erstellen');
$translator->AddTranslation($lang, 'Core.UsergroupForm.CreateContainers', 'Nutzer können Container erstellen');
$translator->AddTranslation($lang, 'Core.UsergroupForm.Submit', 'Speichern');

//user form
$translator->AddTranslation($lang, 'Core.UserForm.Title', 'Backend-Benutzer bearbeiten');
$translator->AddTranslation($lang, 'Core.UserForm.Description', 'Passen Sie hier den Login-Namem, das Password und andere Einstellungen des Benutzers an.');
$translator->AddTranslation($lang, 'Core.UserForm.Legend', 'Benutzereinstellungen');
$translator->AddTranslation($lang, 'Core.UserForm.Name', 'Login-Name');
$translator->AddTranslation($lang, 'Core.UserForm.EMail', 'E-Mail');
$translator->AddTranslation($lang, 'Core.UserForm.Language', 'Sprache');
$translator->AddTranslation($lang, 'Core.UserForm.IsAdmin', 'Ist Administrator?');
$translator->AddTranslation($lang, 'Core.UserForm.Password', 'Neues Passwort (Leerlassen, falls keine Änderung erfolgen soll)');
$translator->AddTranslation($lang, 'Core.UserForm.PasswordRepeat', 'Neues Passwort wiederholen');
$translator->AddTranslation($lang, 'Core.UserForm.Name.Validation.Required.Missing', 'Login-Name erforderlich');
$translator->AddTranslation($lang, 'Core.UserForm.Name.Validation.DatabaseCount.TooMuch', 'Dieser Name ist bereits in Verwendung');
$translator->AddTranslation($lang, 'Core.UserForm.EMail.Validation.Required.Missing', 'E-Mail-Adresse angeben');
$translator->AddTranslation($lang, 'Core.UserForm.EMail.Validation.PhpFilter.InvalidEmail', 'Dies ist keine gültige Mailadresse');
$translator->AddTranslation($lang, 'Core.UserForm.Password.Validation.StringLength.TooShort_{0}', 'Das Passwort muss mindestens {0} Zeichen lang sein');
$translator->AddTranslation($lang, 'Core.UserForm.PasswordRepeat.Validation.Required.Missing', 'Neues Password wiederholen');
$translator->AddTranslation($lang, 'Core.UserForm.PasswordRepeat.Validation.CompareCheck.EqualsNot_{0}', 'Passwörter stimmen nicht überein');
$translator->AddTranslation($lang, 'Core.UserForm.Submit', 'Speichern');

//module (type) form
$translator->AddTranslation($lang, 'Core.ModuleForm.Title', 'Module-Typ');
$translator->AddTranslation($lang, 'Core.ModuleForm.Description', 'Wählen Sie aus, welchen Element-Typ Sie hinzufügen .');
$translator->AddTranslation($lang, 'Core.ModuleForm.Legend', 'Modul auswählen');
$translator->AddTranslation($lang, 'Core.ModuleForm.Module', 'Typ');
$translator->AddTranslation($lang, 'Core.ModuleForm.Module.Validation.Required.Missing', 'Bitte Modul-Typ selektieren um fortzufahren');
$translator->AddTranslation($lang, 'Core.ModuleForm.Submit', 'Weiter');

//navigation titles
$translator->AddTranslation($lang, 'Core.Overview.NavTitle', 'Übersicht');
$translator->AddTranslation($lang, 'Core.SiteList.NavTitle', 'Websites');
$translator->AddTranslation($lang, 'Core.LayoutList.NavTitle', 'Seiten-Layouts');
$translator->AddTranslation($lang, 'Core.ContainerList.NavTitle', 'Containerliste');
$translator->AddTranslation($lang, 'Core.UsergroupList.NavTitle', 'Backend-Gruppen');
$translator->AddTranslation($lang, 'Core.UserList.NavTitle', 'Backend-Nutzer');
$translator->AddTranslation($lang, 'Core.NavTitle', 'Basis-Features');

//container rights
$translator->AddTranslation($lang, 'Core.ContainerRights.Edit', 'Container bearbeiten');
$translator->AddTranslation($lang, 'Core.ContainerRights.Remove', 'Container löschen');


//site rights
$translator->AddTranslation($lang, 'Core.SiteRights.Edit', 'Website bearbeiten');
$translator->AddTranslation($lang, 'Core.SiteRights.Remove', 'Website löschen');

//page rights
$translator->AddTranslation($lang, 'Core.PageRights.Move', 'Seiten verschieben');
$translator->AddTranslation($lang, 'Core.PageRights.Edit', 'Seiten bearbeiten');
$translator->AddTranslation($lang, 'Core.PageRights.Remove', 'Seiten löschen');
$translator->AddTranslation($lang, 'Core.PageRights.CreateIn', 'Neue Seiten erstellen');

//content rights
$translator->AddTranslation($lang, 'Core.ContentRights.Move', 'Inhalte verschieben');
$translator->AddTranslation($lang, 'Core.ContentRights.Edit', 'Inhalte bearbeiten');
$translator->AddTranslation($lang, 'Core.ContentRights.Remove', 'Inhalte löschen');
$translator->AddTranslation($lang, 'Core.ContentRights.CreateIn', 'Inhalte erstellen');

//layout rights
$translator->AddTranslation($lang, 'Core.LayoutRights.Edit', 'Layout bearbeiten');
$translator->AddTranslation($lang, 'Core.LayoutRights.Remove', 'Layout löschen');

//common content form fields
$translator->AddTranslation($lang, 'Core.ContentForm.CssID', 'HTML-ID');
$translator->AddTranslation($lang, 'Core.ContentForm.CssClass', 'CSS-Klasse(n)');
$translator->AddTranslation($lang, 'Core.ContentForm.UserGroup', 'Backend-Nutzergruppe & Rechte');
$translator->AddTranslation($lang, 'Core.ContentForm.UserGroup.Inherit_{0}', '-- Übergeordnete Einstellung übernehmen ({0}) --');
$translator->AddTranslation($lang, 'Core.ContentForm.UserGroup.Inherit', '-- Übergeordnete Einstellung übernehmen (Keine Nutzergruppe) --');
$translator->AddTranslation($lang, 'Core.ContentForm.Template', 'Template');
$translator->AddTranslation($lang, 'Core.ContentForm.Template.Default', 'Standard verwenden');
$translator->AddTranslation($lang, 'Core.ContentForm.CacheLifetime', 'Cache-Lebensdauer (in Sekunden)');
$translator->AddTranslation($lang, 'Core.ContentForm.CacheLifetime.Validation.Integer.HasNonDigits', 'Eine ganze Zahl eingeben für die Cachedauer');

//template list
$translator->AddTranslation($lang, 'Core.TemplateList.NavTitle', 'Templates');
$translator->AddTranslation($lang, 'Core.TemplateList.Title', 'Templates');
$translator->AddTranslation($lang, 'Core.TemplateList.Description', 'Die Templates können hier bearbeitet werden. Sie sind nach Bundle und Modul sortiert.');
$translator->AddTranslation($lang, 'Core.TemplateList.Create', 'Neues Template für dieses Modul erstellen');
$translator->AddTranslation($lang, 'Core.TemplateList.Edit', 'Template bearbeiten');
$translator->AddTranslation($lang, 'Core.TemplateList.Delete', 'Template löschen');

//template form
$translator->AddTranslation($lang, 'Core.TemplateForm.Title', 'Template bearbeiten');
$translator->AddTranslation($lang, 'Core.TemplateForm.Description', 'Passen Sie hier den Namen und den Inhalt des Templates an.');
$translator->AddTranslation($lang, 'Core.TemplateForm.Legend', 'Template-Einstellungen');
$translator->AddTranslation($lang, 'Core.TemplateForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.TemplateForm.Contents', 'Inhalt');
$translator->AddTranslation($lang, 'Core.TemplateForm.Submit', 'Speichern');

$translator->AddTranslation($lang, 'Core.TemplateForm.Name.Validation.Required.Missing', 'Namen für das Template eingeben');
$translator->AddTranslation($lang, 'Core.TemplateForm.Name.Validation.FileExists.InFolder_{0}', 'Dieser Template-Name wird bereits verwendet');
$translator->AddTranslation($lang, 'Core.TemplateForm.Name.Validation.RegExp.NoMatch', 'Nur Zeichen verwenden, die auf allen Plattformen in Dateinamen erlaubt sind');

//common button texts
$translator->AddTranslation($lang, 'Core.ButtonText.Back', 'Zurück');

//exception error texts
$translator->AddTranslation($lang, 'Core.Replacer.Error.PageNotFound.ID_{0}', 'Fehler beim Ersetzen einer Seitenvariable: Seite {0} nicht gefunden');
$translator->AddTranslation($lang, 'Core.Replacer.Error.SiteNotFound.ID_{0}', 'Fehler beim Ersetzen einer Seitenvariable: Website {0} nicht gefunden');
$translator->AddTranslation($lang, 'Core.Replacer.Error.FilterNotFound.Name_{0}', 'Error replacing einer Seitenvariable: Filterfunktion {0} existiert nicht');
$translator->AddTranslation($lang, 'Core.Form.Error.ElementNotFound.Name_{0}', "Es wurde versucht das undefinierte Formelement namens '{0}' aufzurufen.");
$translator->AddTranslation($lang, 'Core.CacheKey.Error.NotAlphaNumeric', 'Cache-Keys dürfen nur aus Zahlen und Buchstaben bestehen');
$translator->AddTranslation($lang, 'Core.FieldColumnizer.Error.MaxColumns.MustDivide12', 'Die maximale Spaltenzahl muss ein Teiler von 12 sein');

//overview texts
$translator->AddTranslation($lang, 'Core.Overview.Title', 'Willkommen im Phine CMS!');
$translator->AddTranslation($lang, 'Core.Overview.Description', 'Hier sind die verfügbaren Backend-Module. Sie gelangen in den gewünschten Bereich, wenn Sie die jeweilige Überschrift anklicken. Außerdem können Sie die globalen Einstellungen hier ändern.');
$translator->AddTranslation($lang, 'Core.Overview.Settings.LinkText', 'Globale Einstellungen');
$translator->AddTranslation($lang, 'Core.OverviewTitle', 'Basis-Features');
$translator->AddTranslation($lang, 'Core.OverviewDescription', 'Die Basis-Features beinhalten alle Funktionen, die Sie benötigen um Ihre Websites mit dem Phine Content Management System zu verwalten.');
$translator->AddTranslation($lang, 'Core.ContainerList.OverviewTitle', 'Containerliste');
$translator->AddTranslation($lang, 'Core.ContainerList.OverviewDescription', 'Container sind wiederverwendbare Einheiten, in denen Sie Inhalte ablegen können um Sie an unterschiedlichen Stellen zu platzieren.');
$translator->AddTranslation($lang, 'Core.LayoutList.OverviewTitle', 'Layouts');
$translator->AddTranslation($lang, 'Core.LayoutList.OverviewDescription', 'Layouts definieren die inhaltliche Struktur Ihrer Webseiten. Dazu werden die Inhalte in verschiedene Bereiche eingefügt, die in einer HTML-Vorlage (Template) integriert sind.');
$translator->AddTranslation($lang, 'Core.SiteList.OverviewTitle', 'Websites');
$translator->AddTranslation($lang, 'Core.SiteList.OverviewDescription', 'In einer Phine CMS-Installation können Sie mehrere Webseiten verwalten. Legen Sie einen neuen Webauftritt an oder folgen Sie Links der Websites in der Liste um sie zu verwalten.');
$translator->AddTranslation($lang, 'Core.TemplateList.OverviewTitle', 'Templates');
$translator->AddTranslation($lang, 'Core.TemplateList.OverviewDescription', 'Einige Inhaltsmodule bieten Ihnen die Möglichkeit, ihre HTML-Ausgabe genau zu steuern. Dies erfolgt mit Hilfe von HTML-Templates, die Sie hier anpassen können.');
$translator->AddTranslation($lang, 'Core.UsergroupList.OverviewTitle', 'Backend-Benutzergruppen');
$translator->AddTranslation($lang, 'Core.UsergroupList.OverviewDescription', 'Phine ist ein vollwertiges Web-Redaktionssystem, bei dem Zugriffsrechte über Gruppen vergeben werden. Diese Backend-Gruppen können hier erstellt und bearbeitet werden.');
$translator->AddTranslation($lang, 'Core.UserList.OverviewTitle', 'Backend-Benutzerliste');
$translator->AddTranslation($lang, 'Core.UserList.OverviewDescription', 'Phine ist ein Multi-User-CMS. Es kann eine beliebige Anzahl von Benutzern angelegt werden, die Sie hier administrieren können.');
$translator->AddTranslation($lang, 'Core.MembergroupList.OverviewTitle', 'Frontend-Gruppen');
$translator->AddTranslation($lang, 'Core.MembergroupList.OverviewDescription', 'Den Frontend-Nutzern (Mitgliedern) können Sie Gruppen zuweisen. Seiten und Elemente lassen sich auf einzelne Frontend-Gruppen beschränken.');
$translator->AddTranslation($lang, 'Core.MemberList.OverviewTitle', 'Frontend-Nuter');
$translator->AddTranslation($lang, 'Core.MemberList.OverviewDescription', 'Die Liste der Website-Miglieder mit speziellen Zugriffsrechten ist hier zu finden. Nur Backend-Administratoren dürfen ihre Eigenschaften ändern.');

//ajax page / page url selection
$translator->AddTranslation($lang, 'Core.PageUrlSelector.NoPage', '- Keine URL ausgewählt -');
$translator->AddTranslation($lang, 'Core.PageUrlSelector.OpenModal', 'Neue URL zuweisen');
$translator->AddTranslation($lang, 'Core.PageSelector.NoPage', '- Keine Seite ausgewählt -');
$translator->AddTranslation($lang, 'Core.PageSelector.OpenModal', 'Neue Seite zuweisen');

$translator->AddTranslation($lang, 'Core.AjaxSelectPage.NoPage', '- Seitenauswahl aufheben -');
$translator->AddTranslation($lang, 'Core.AjaxSelectPage.Submit', 'Weiter');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.Legend', 'Parameter & Fragment-ID');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.OptionalParameters', 'Optionale Parameter');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.OptionalParameters.Placeholder', 'Eine Zuweisung pro Zeile; Format: param=wert');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.Fragment', 'URL-Fragment');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.Fragment.Placeholder', 'HTML-ID innerhalb der Zielseite; optional');
$translator->AddTranslation($lang, 'Core.AjaxPageParams.Submit', 'Speichern');

$translator->AddTranslation($lang, 'Core.AjaxPageParams.Param.Validation.Required.Missing', 'Die gewählte Seite erfordert einen Wert für diesen Parameter');

//member list
$translator->AddTranslation($lang, 'Core.MemberList.NavTitle', 'Frontend-Nutzer');
$translator->AddTranslation($lang, 'Core.MemberList.Title', 'Frontend-Nutzerliste');
$translator->AddTranslation($lang, 'Core.MemberList.Description.Amount_{0}', 'Hier sind die registrierten Frontend-Benutzer Ihrer Seiten aufgelistet. Derzeit sind {0} Mitglieder registriert.');
$translator->AddTranslation($lang, 'Core.MemberList.New', 'Neues Mitglied anlegen');
$translator->AddTranslation($lang, 'Core.MemberList.Name', 'Anmeldename');
$translator->AddTranslation($lang, 'Core.MemberList.Edit', 'Nutzer bearbeiten');
$translator->AddTranslation($lang, 'Core.MemberList.Delete', 'Nutzer entfernen');

//member form
$translator->AddTranslation($lang, 'Core.MemberForm.Title', 'Mitglied bearbeiten');

$translator->AddTranslation($lang, 'Core.MemberForm.Description', 'Bearbeiten Sie hier die Einstellungen des Frontend-Users.');
$translator->AddTranslation($lang, 'Core.MemberForm.Legend', 'Benutzer-Einstellungen');
$translator->AddTranslation($lang, 'Core.MemberForm.EMail', 'E-Mail');
$translator->AddTranslation($lang, 'Core.MemberForm.Name', 'Anmeldename');
$translator->AddTranslation($lang, 'Core.MemberForm.Password', 'Passwort');
$translator->AddTranslation($lang, 'Core.MemberForm.Password.Placeholder', 'Leer lassen, falls keine Änderung erwünscht ist');
$translator->AddTranslation($lang, 'Core.MemberForm.MemberGroup', 'Nutzergruppen zuweisen');
$translator->AddTranslation($lang, 'Core.MemberForm.Submit', 'Speichern');

$translator->AddTranslation($lang, 'Core.MemberForm.EMail.Validation.Required.Missing', 'E-Mail-Adresse wird benötigt');
$translator->AddTranslation($lang, 'Core.MemberForm.Name.Validation.Required.Missing', 'Anmeldename erforderlich');
$translator->AddTranslation($lang, 'Core.MemberForm.Password.Validation.Required.Missing', 'Passwort muss bei neuem Nutzer gesetzt werden');
$translator->AddTranslation($lang, 'Core.MemberForm.Password.Validation.StringLength.TooShort_{0}', 'Das Passwort muss mindestens {0} Zeichen beinhalten');

//member group list
$translator->AddTranslation($lang, 'Core.MembergroupList.NavTitle', 'Frontend-Gruppen');
$translator->AddTranslation($lang, 'Core.MembergroupList.Title', 'Mitgliedergruppen');
$translator->AddTranslation($lang, 'Core.MembergroupList.Description.Amount_{0}', 'Mit Hilfer der Gruppen für Frontend-Nutzer können Sie Inhaltsbereiche gezielt für gewisse Nutzer freigeben oder sperren. Derzeit sind {0} Mitgliedergruppen vorhanden.');

$translator->AddTranslation($lang, 'Core.MembergroupList.New', 'Neue Gruppe erstellen');
$translator->AddTranslation($lang, 'Core.MembergroupList.Name', 'Gruppenname');
$translator->AddTranslation($lang, 'Core.MembergroupList.Edit', 'Gruppe bearbeiten');
$translator->AddTranslation($lang, 'Core.MembergroupList.Delete', 'Gruppe löschen');

// member group form
$translator->AddTranslation($lang, 'Core.MembergroupForm.Title', 'Mitgliedergruppe bearbeiten');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Description', 'Legen Sie hier die Eigenschaften der Gruppe von Frontendnutzern fest.');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Legend', 'Gruppen-Eigenschaften');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Name', 'Name');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Submit', 'Speichern');

$translator->AddTranslation($lang, 'Core.MembergroupForm.Name.Validation.Required.Missing', 'Name der Gruppe fehlt');
$translator->AddTranslation($lang, 'Core.MembergroupForm.Name.Validation.DatabaseCount.TooMuch', 'Es existiert schon eine andere Gruppe mit diesem Namen');

// settings form
$translator->AddTranslation($lang, 'Core.SettingsForm.Title', 'Globale Einstellungen');
$translator->AddTranslation($lang, 'Core.SettingsForm.Description', 'Stellen Sie hier globale Einstellungen für Mailing und Logging zusammen.');
$translator->AddTranslation($lang, 'Core.SettingsForm.Legend', 'Log & Mail-Basiseinstellungen');
$translator->AddTranslation($lang, 'Core.SettingsForm.LogLifetime', 'Lebensdauer Logeinträge; in Tagen');
$translator->AddTranslation($lang, 'Core.SettingsForm.LogLifetime.Validation.Integer.ExceedsMax_{0}', 'Die Lebensdauer beträgt maximal {0} Tage');
$translator->AddTranslation($lang, 'Core.SettingsForm.ChangeRequestLifetime', 'Lebensdauer Userlinks (z.B. Neues Passwort); in Tagen');
$translator->AddTranslation($lang, 'Core.SettingsForm.MailFromEMail', 'Absender-E-Mailadresse');
$translator->AddTranslation($lang, 'Core.SettingsForm.MailFromEMail.Validation.PhpFilter.InvalidEmail', 'Diese Mailadresse ist ungültig');
$translator->AddTranslation($lang, 'Core.SettingsForm.MailFromName', 'Absendername bei Mailversand');
$translator->AddTranslation($lang, 'Core.SettingsForm.Smtp.Legend', 'SMTP-Einstellungen');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpHost', 'Hostrechner');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpPort', 'Portnummer');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpPort.Validation.Integer.HasNonDigits', 'Port muss eine Zahl sein');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpPort.Validation.Integer.ExceedsMax_{0}', 'Höchster Port ist 65535');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpSecurity', 'Sichereit');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpSecurity.None', 'Keine Sichereit');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpSecurity.Ssl', 'SSL');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpSecurity.Tls', 'TLS');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpUser', 'Nutzername');
$translator->AddTranslation($lang, 'Core.SettingsForm.SmtpPassword', 'Passwort');
$translator->AddTranslation($lang, 'Core.SettingsForm.Submit', 'Speichern');

// member groups
$translator->AddTranslation($lang, 'Core.PageForm.MemberGroup', 'Zugriff auf Frontend-Gruppen beschränken');
$translator->AddTranslation($lang, 'Core.ContentForm.MemberGroup', 'Zugriff auf Frontend-Gruppen beschränken');
$translator->AddTranslation($lang, 'Core.PageForm.GuestsOnly', 'Nur Gästen anzeigen');
$translator->AddTranslation($lang, 'Core.ContentForm.GuestsOnly', 'Nur Gästen anzeigen');
$translator->AddTranslation($lang, 'Core.ContentForm.Publish', 'Veröffentlichen');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishFromDate', 'Sichtbar ab');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishFromHour', 'Std.');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishFromMinute', 'Min.');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishToDate', 'Sichtbar bis');
$translator->AddTranslation($lang, 'Core.PageForm.PublishToHour', 'Std.');

$translator->AddTranslation($lang, 'Core.ContentForm.PublishToHour', 'Std.');
$translator->AddTranslation($lang, 'Core.ContentForm.PublishToMinute', 'Min.');

$translator->AddTranslation($lang, 'Core.ContentForm.Wording.Placeholder', '<Standard verwenden>');

$translator->AddTranslation($lang, 'Core.ContentForm.Legend.Access', 'Zugriffsrechte');
$translator->AddTranslation($lang, 'Core.ContentForm.Legend.Wordings', 'Texte');