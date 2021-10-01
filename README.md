* [Branches](#branches)
* [What is this?](#what-is-this)
* [Helper page?](#helper-page)
* [Examples](#examples)  
* [Installation and configuration](#installation-and-configuration)
* [Issues and feedback](#issues-and-feedback)
* [Credits and Thanks](#credits-and-thanks)

Branches
--------

For Moodle 3.9 Onwards, use the master branch.


What is this?
-------------
    
This is pretty much a copy of core block_html, but with the possibility to use placeholders in HTML content.  

A lists of known placeholders with their default values could be preconfigured on a site level .

There is some logic involved for figuring out what value needs to be set for each of the placeholders.

1. Try to get values from URL ( if that happens then those values are saved for the user).
2. Try to get values from user preferences.
3. Fall back to the default values from config.


Helper page
-------------

There is a complimentary page included with this plugin. This page uses my dashboard layout and can be used as an 
additional page for providing some information as well as setting up some placeholders for users 
(even if you don't have html_placeholders b lock on this page).
 
The page can also be used just for setting up your placeholders for a user and then redirecting to any of the Moodle pages.  


Examples
-------------
1. Your Moodle front page has an instance of html_placeholders block with {{profession}} placeholder in its content.
   
   https://example.com/?profession=Doctor -> will replace {{profession}} with Doctor 
   
   https://example.com/?profession=Nurse  -> will replace {{profession}} with Nurse

2. Your Moodle front page has an instance of html_placeholders block with {{profession}} and {{type}} placeholders in 
   its content. 
   
   https://example.com/blocks/html_placeholders/landing.php?redirect=/&profession=Doctor&type=On-line -> will set  profession and type for a user and then redirect to the front page, where will replace {{profession}} with Doctor and  {{type}} with On-line


Installation and Configuration
------------------------------

1. Install the same as any other moodle plugin:

    Using git

     git clone git@github.com:catalyst/moodle-html_placeholders.git blocks/html_placeholders

    Or install via the Moodle plugin directory:

     https://moodle.org/plugins/html_placeholders

2. Then run the Moodle upgrade

4. Visit Site Administration -> Plugins -> Blocks -> HTML with placeholders and configure the list of placeholders.

3. Now add the block to any page, then you can embed your placeholders as {{placeholder_name}} in HTML content or title. 


Contributing
------------

Pull requests are welcome, please adhere to the Moodle code standards.

Issues and feedback
-------------------

If you have issues please log them in github here:

https://github.com/catalyst/html_placeholders/issues

Or if you want paid support please contact Catalyst IT Australia:

https://www.catalyst-au.net/contact-us


Credits and thanks
------------------

This plugin was sponsored by OET Online:

https://oetonline.net.au/


This plugin was developed by Catalyst IT Australia:

https://www.catalyst-au.net/
