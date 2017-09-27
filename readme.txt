=== Custom Classes ===

Contributors: greenshady
Donate link: https://themehybrid.com/donate
Tags: classes
Requires at least: 4.8
Tested up to: 4.8.2
Requires PHP: 5.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows users to create custom classes on a per-post/term basis.

== Description ==

The Custom Classes plugin allows you to create custom classes for posts (when `post_class()` is called) and for the `<body>` class (when `body_class()` is called).  It creates a meta box on the edit post and edit term screens in the admin with input boxes for adding your custom classes.

Custom post classes are added whenever your theme calls the `post_class()` function, which is generally whenever a post is shown.  Custom body classes are added on the single view of the post whenever your theme calls the `body_class()` function.

### Like this plugin?

Please consider helping the cause by:

* [Making a donation](https://themehybrid.com/donate).
* [Signing up at my site](https://themehybrid.com/club).
* [Rating the plugin](https://wordpress.org/support/view/plugin-reviews/custom-classes?rate=5#postform).

### Professional Support

If you need professional plugin support from me, the plugin author, you can access the support forums at [Theme Hybrid](https://themehybrid.com/board/topics), which is a professional WordPress help/support site where I handle support for all my plugins and themes for a community of 75,000+ users (and growing).

### Plugin Development

If you're a theme author, plugin author, or just a code hobbyist, you can follow the development of this plugin on it's [GitHub repository](https://github.com/justintadlock/custom-classes).

== Frequently Asked Questions ==

### Why was this plugin created?

I wanted a quick and easy way to add styles to my posts without having to make a tag, create a category, or use post formats.  Those solutions are great, but they're not ideal for every situation.  By using a custom class, I have complete control over post styles.

### How do I use it?

A meta box named "Classes" gets added to the edit post screen in the admin.  From there, you can input a custom post and/or body class for individual posts.  On the edit/new term (category, tag, etc.) screen, a new field labeled "Body Class" is also available.

Of course, you actually have to style the class in your theme's `style.css` file for anything to actually change on the front end.  This plugin just outputs the classes for you.  It's up to you to decide how to use them.

## Will this work with custom post types?

Certainly.  This plugin works with any public post type on your site.  It's not just limited to the "post" post type.

### It's not working!

Most likely, this means your theme isn't using the appropriate functions (`post_class()` and `body_class()`).  You'll need to talk to your theme author to get them to fix this.  Or, better yet, use a correctly-coded theme from <a href="https://themehybrid.com/themes">Theme Hybrid</a>!

== Screenshots ==

1. Custom classes meta box
2. Body class output in source code

== Changelog ==

The change log is located in the `changelog.md` file in the plugin folder.  You may also [view the change log](https://github.com/justintadlock/custom-classes/blob/master/changelog.md) online.
