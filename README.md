# wcioShop
A ecommerce solution focused on speed and reliability. Long story short, the first version of this system was built in 4 day non stop coding (YES i did not sleep for days coding this). 
This system have been in use for since 2013 but never made public, this github project will fix that. I will be making a lot of changes to this before adding files to GitHub. Expecting 300 hours of coding for a working version. 

## Requirements
- PHP 7.4+ (Developed on APache with PHP 7.4)
- A valid and working SSL certificate

## FAQ
### Is there a live demo
Not right now.

#### Where is the SQL?
SQL structure and demo content can be found in SQL.

#### How do i make my own theme?
In the directory /templates/ you will find a theme called default. This is a bootstrap theme you can edit if you want. TO make your own theme you need to know how Smarty template engine works (Not hard at all). Here is a link for the Smarty template engine documentation: https://www.smarty.net/documentation

#### I need a new function in my templates, how do i add one
This will be explained in the documentation at some point, but here is a quick one.
In /inc/template-functions/ you will find some .php files. THeese are functions that auto loads IF they are used in a .tpl file. 
If you write {$maintenanceMode} in your template, the file /inc/template-functions/maintenanceMode.php will be loaded into the code and available on the site. The same if you use the section function from smarty. 
Much more detals will be available on this in the future. PLease ask if you need help with this (Use issue tracker)

#### What about SEO
SEO is important and i will implement as much as i can to make this a none issue for you who work with SEO to implement what you need. 

## Issues / Bug report
If you experience any errors, please report htem at the link below. If you can provide screenshots too.
Go here for issues or bug report: https://github.com/websitecareio/wcioShop/issues

## Want to help?
There is currently TWO diffent administrations made, and there will be made another one. While i code this new version i will be using PHPmyAdmin to add / edit content.

What the administraiton needs is:
1) A coded design in bootstrap 4 (Got something i like, just need to code it)
2) Working PHP code
3) Working temaplte files (Using smarty template engine).

## Documentation
There will be made several documentations. All can be found at link below:

https://github.com/websitecareio/wcioShop/wiki

### Want to know more?
Contact me at support@websitecare.io 

### Todo
- Needs SEO system. Specificcally a permalink system to handle the URLs. Old one used something really bad. 
- And a lot more...
