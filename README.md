# Minifier
## You're going to love me!
Hi, my name is Minifier! You have probably seen a lot like me, but I am unique! 

I am very lightweight, only 150 lines of PHP, 5.5 kB. I am just a single file so your project folder remains nice even with me!

##What do I do
As you may already know, my primary goal is to minify huge things - CSS and JavaScript files.

I reduce **all of them** into just two single files stored in your Cache folder. There's no further need to load *jQuery, Bootstrap, Font Awesome* etc on their own, I do it all for you.

My syntax is really easy. You just *add* files to me, I *compile and minify* them and then you *render*. That's it!

Plus I'am of course fully objective so you can easily change me as you need!

##Examples
###Get started
All you need is to load the *Minifier.php* file
``` 
require_once "Minifier.php"; 
```
and create folder called *cache* in the same directiory.

###Simple scripts and styles
You can add as many files as you want, either in an array or as a single string.
```
 $minifier = new Minifier();     // create new instance of me
 $minifier->add(array(           // gimme some files
     "index.js",
     "index.css"
 ));
 $minifier->add("cookies.js");
 $minifier->add("tables.css");
```

###External files
I love external libraries just as much as I love yours!
```
 $minifier->add(array(
     "https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js",        // jQuery
     "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css",   // Bootstrap
 ));
```

###Complicated file type
I can guess file type by its suffix, but sometimes I could struggle with the decision of some crazy URLs' type. That's why you can add the second parameter to help me know!
```
 $minifier->add("https://fonts.googleapis.com/css?family=Open+Sans", "css");  // the second parameter is useful especially with fonts
```

###Cycles
Probably my most favourite part of my abilities are cycles. You can simply load all files in one folder, for example
```
$files = glob('styles/*');      // get all styles in a folder
foreach($files as $file){       // cycle through files
    if(is_file($file))
        $minifier->add($file);  // gimme the file!
}
```

###Rendering
When you're done adding all your files, you can render the files. The files physically save just as they render. You can name them by their version and you can choose wheter or not you want to minify them.
```
 $minifier->render();                // files minify with default names
 $minifier->render(false, "0.0.1");  // no minifying, files get suffix -0.0.1
```

###Deleting cache
Is your cache folder full of messy files of different versioins? Clear them!
```
$minifier->clear();       // clear all files in Cache folder
```
**Warning** - if you render on the same page as you clear, your files will delete *immediately* after the rendering so they can't be used!

##Methods
| Name          | Parameters                                                                          | Description                                        |
| ------------- | -------------                                                                       | -------------------------------------------------- |
| add           | **source** (*String*, *Array*), **type** (*String* "css" or "js"; optional)         | add files to be rendered                           |
| render        | **minify** (*Boolean*; default *true*; optional), **version** (*String*; optional)   | saves and renderes files, returns echoed HTML tags |
| clear         | -                                                                                   | deletes all files in the Cache folder              |

Give me a try please! I am absolutely free, simple and smart, your websites will love me!
