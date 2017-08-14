=== Custom Classes ===
Contributors: greenshady
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3687060
Tags: admin, password
Requires at least: 3.3
Tested up to: 3.3.1
Stable tag: 0.1

Allows users to create custom post and body classes on a per-post basis.

== Description ==

The Custom Classes plugin allows you to create custom classes for posts (when `post_class()` is called) and for the `<body>` class (when `body_class()` is called).  It creates a meta box on the edit post screen in the admin with two input boxes for adding your custom classes.

Custom post classes are added whenever your theme calls the `post_class()` function, which is generally whenever a post is shown.  Custom body classes are added on the single view of the post whenever your theme calls the `body_class()` function.

== Installation ==

1. Upload `custom-classes` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to your user profile in the admin to select whether to prevent password resetting.

== Frequently Asked Questions ==

= Why was this plugin created? =

I wanted a quick and easy way to add styles to my posts without having to make a tag, create a category, or use post formats.  Those solutions are great, but they're not ideal for every situation.  By using a custom class, I have complete control over post styles.

= How do I use it? =

A meta box named "Classes" gets added to the edit post screen in the admin.  From there, you can input a custom post and/or body class for individual posts.

Of course, you actually have to style the class in your theme's `style.css` file for anything to actually change on the front end.  This plugin just outputs the classes for you.  It's up to you to decide how to use them.

= Will this work with other post types? =

Certainly.  This plugin works with any public post type on your site.  It's not just limited to the "post" post type.

= It's not working! =

Most likely, this means your theme isn't using the appropriate functions (`post_class()` and `body_class()`).  You'll need to talk to your theme author to get them to fix this.  Or, better yet, use a correctly-coded theme from <a href="http://themehybrid.com">Theme Hybrid</a>!

== Screenshots ==

1. Custom classes meta box
2. Body class output in source code

== Changelog ==

**Version 0.1**

* Plugin launch.  Everything's new!