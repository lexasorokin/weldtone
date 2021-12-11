#################################################################################################
#
# This CHANGELOG.md file will contain the steps needed to done by the IT department to roll out
# any change to this website. This includes backend changes and database modifications. Please
# be as detailed as possible so that we can move your changes to the staging and production
# successfully.
#
# Please include the Project ID and Git Hub branch for your changes.
#
#################################################################################################

## Project #13988 Civic Start D9 Instance (from MesaWater D9 Site)
- Created D9 instance of MesaWater (D9) for Civic Starter D9 Upgrade.
- Created dev-d9 branch of Civic Starter Repo on Git Hub.

##Project #13673 Duplicate Mesa Water
Code Changes :
- Updated all custom module info files with support of drupal 8|9
- Changed deprecated code for path alias manager and user temp services from civic theme file
- Updated contrib modules (Composer file)

Database Changes :
- Added status report modules and updated contrib modules and core for drupal 9 support

Steps to perform :
- drush pm:uninstall kint
- Composer Install
- drush cache cr
- drush updatedb  (ignore Unable to decode output into JSON: Syntax error)


