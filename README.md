# REDCap Warning Banner

This REDCap External Module allows administrators to display a warning banner to survey respondents, and project users on the invite participants page, if the project is of purpose 'Practice', or otherwise, is still in development mode. Each warning is separately enabled, and can have customised warning text.

Warnings can be displayed on Survey Distribution Tools pages, record home pages, data entry pages, and survey pages, and use core REDCap CSS classes, as shown in this screenshot:

<img src="WarningBanner.png" style="width: 800px; border: 1px solid;"/>

## Why?

The built-in protections against users accidentally sending survey links and invitations to respondents before their project has been moved to production, are not strong. There is a yellow warning on the Add/Edit Records page warning users that they should not collect real data until the project is in production, but such warnings do not appear in all places where a user may accidentally distribute a survey to respondents, and even where they do appear, they may be ignored.

This module allows administrators to add more visibility to their site's business rules, and thus improve compliance.

## Installation

Install the module from the REDCap module repository and enable in the Control Center, then enable on all projects.

## Usage

Administrators may separately enable – and customise both text and CSS class colour of – warnings for practice projects, or projects of other purposes that are in development, and separately for project users, or survey respondents. Administrators may additionally convert the existing warning on the Add/Edit Records page from yellow to whatever built-in CSS colour class desired.

Administrators may also override these settings on a per-project level, either disabling the warnings or customising the warning text and colour.

## Localisation

This module supports localisation through the REDCap External Module Framework's Internationalisation (i18n) features. All configuration options, default warning text, and module name and description, may be translated by duplicating the file lang/English.ini to your local language and replacing the values of each text parameter. If you translate this module to a new language, consider contributing your translation to this repository so that it can be included in future releases for other sites.

## Limitations

- Only yellow, orange and red exclamation images are present in REDCap's resources, and at the moment, only the red exclamation icon is used. This may mean for incongruous colour combinations if the Add/Edit Records page warning is changed to a colour other than red. Future versions may allow the use of Font Awesome icons to allow for more customisation.
- The existing warning on the Add/Edit Records page is only displayed for Development projects, whereas the priority of this module is to display the practice project warnings instead. This can lead to a situation where the existing warning is not displayed, but the practice project warning is. Future versions may allow for the default warning to be customised or replaced.

## TODO

- Allow configuration options to modify the banner style, or select from several templates.
- Employ Font Awesome to allow customisation of the exclamation icon.

## Contributing

If you would like to contribute to this module, please consider forking this repository, making your changes, and submitting a pull request. If you have any questions, please contact the author.

## Changelog

| Version | Description                                                                                           |
| ------- | --------------------                                                                                  |
| v1.0.0  | Initial release.                                                                                      |
| v1.1.0  | Adds ability to configure warning colour, using built-in REDCap CSS classes.                          |
| v1.1.1  | Adds ability to configure colour of existing warning on Add/Edit Records page.                        |
| v1.2.0  | Add warning to record home page and data entry pages.                                                 |
