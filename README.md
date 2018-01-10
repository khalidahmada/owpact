# OWPACT #

OWPACT is basic php CLI for **Wordpress** project.Allows you to speedup production into wordpress Project. inspired from `artisan` of **laravel** 

### How do I get set up? ###

* get owpact folder
* copy config/project.json.dist to config/project.json
* add into the file your project name as key and the path to your current theme for example `"tuto":"../Path_to_Theme`
* Add the projct key to **current_dist into** 'project.json' for our case will be `"current_dist":"tuto"`
* Once you configure the `config/project.json` you have now owpact
* Run your first owpact command you need to publish the boilereplate to your theme by simple command `php owp publish` this will clone the **owp/ressources/templates** to your theme by defaut **your_theme/inc/owp**
* great you have know owp in your project

to get help type `php owp help` to see list of features.

### Files strictures  ###
### Features  ###

* Writing tests
* Code review
* Other guidelines

### Author  ###
Khalid Ahmada
Follow on Twitter: https://twitter.com/khalidahmada
