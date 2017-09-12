Adding CSS/JS?
=============================

First off, we don't reccommend changing source CSS/JS files - it makes it super hard to upgrade your theme

To add custom css / js:
- Consider using a child theme
- or, simply add css (or, javascript) via the Theme's Admin Page (reccomended)

To add HTML:
- use the "advanced" module in your page or posts. It lets you drop-in HTML anywhere on your page (reccomended)
- Also, if you know PHP we have a bunch of wordpress hooks to inject HTML throughout your site (find hooks in page.php)

-----------
Advanced (Breaks theme updates)
-----------

If you must modify the assets... here's what you need to know.
We use a custom compiler to take raw SASS(CSS)/JS files and package it up ready to be loaded into the website.

** We don't provide support for this. There's plenty of existing support online for node + gulp **

Raw source files are located in: /src/
Compiled files are in: /dist/
You never want to edit files in /dist/, only edit /src/ files

We use Node & Gulp via the command line to compile our files. To do this:

A. Install Node: https://nodejs.org/en/download/
B. Install Gulp: "npm install gulp -g"

In your command line navigate to the assets/ folder, then:

1. Install Node Libraries: "npm install"
2. Run gulp, to package up the changed src files into dist/: "gulp"

...Again, we don't provide support for this & it will break theme updates

-----------

Happy coding! - ThemeLot & the Studio Brace Team
