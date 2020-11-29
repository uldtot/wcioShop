# wcioShop
A ecommerce solution focused on speed and reliability. Long story short, the first version of this system was built in 4 day non stop coding (YES i did not sleep for days coding this). 
This system have been in use for since 2013 but never made public, this github project will fix that. I will be making a lot of changes to this before adding files to GitHub. If you see this message, the project is not ready for use. 

## Requirements
- PHP 7.4+ (Developed on PHP 7.4)
- Apache 2.4+ web server (Should work on NGINX or LiteSpeed but without the .htaccess)
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
I would like help with this. Currently my biggest problem is the administration template. I know what i want it to look like, but need it coded in Bootstrap 4. Let me know if you want to help :)

## Documentation
There will be made several documentations. All can be found at link below:

https://github.com/websitecareio/wcioShop/wiki

### Want to know more?
Contact me at support@websitecare.io 

### Todo
- Redirect module (301 or 302)
- Make sure the cache work for headerCart
- AppStore to show what can be done with the modules available.
- And a lot more...
