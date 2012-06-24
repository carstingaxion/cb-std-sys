=== Comment Moderation E-mail to Post Author ===
Contributors: RavanH
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ravanhagen%40gmail%2ecom&item_name=Comment%20Moderation%20E-mail%20to%20Post%20Author&item_number=0%2e1&no_shipping=0&tax=0&bn=PP%2dDonationsBF&charset=UTF%2d8&lc=us
Tags: comments, moderation, comment, e-mail, author, comment notification, moderation queue
Requires at least: 1.0
Tested up to: 3.0.4
Stable tag: 0.1

Makes WordPress send the comment moderation notification to the actual posts author's e-mail address instead of the main site e-mail address.

== Description ==

When a comment gets posted to a particular post, the auhtor of that post gets a notification about it. However, when that comment is held for moderation, the author receives nothing but instead the moderation notification is sent to the sites **Administrative moderator E-mail address** as configured under **Settings > General**.

For many blog or site owners, this might boil down to the same thing. But for **colaboration sites** where different people post, this might be kind of a clumsy way of handling moderation messages. Exactly the one that should be moderating the comments to his/her own post, is left out of the loop! Only by logging in from time to time, the author can see if there is any need to moderate comments. While on the other hand the site owner, with enough on his/her mind already, is bothered with each and every new comment in the moderation queue.

This plugin changes that. **Just install, activate it and it's done...** All post comment moderation notifications will be sent to the respective **Post Author**. If, by any chance, there is no author e-mail the default site admin e-mail will be used.

Works on WordPress in both Normal and Multi-site mode.

== Installation ==

[Install now](http://coveredwebservices.com/wp-plugin-install/?plugin=comment-moderation-e-mail-to-post-author)

== Frequently Asked Questions ==

= I see no settings page =
There is no settings page. The plugin will do only *one thing* : make comment moderation notifications go to the authors e-mail address, not the site moderator address. 

= Nothing looks different. Is it working at all? =
To test if it is working:

1. Check your Settings > Discussion settings and make sure that (I) at **E-mail me whenever** at least *A comment is held for moderation* and (II) at **Before a comment appears** at least *Comment author must have a previously approved comment* are checked.
2. Log out and clear your browser cookies & cache.
3. As an anonymous visitor, post a comment to a post from anyone other than the main site owner.
4. Log back in, verify that comment went into the moderation queue and then ask the author if he/she received a moderation notification about it :)

= Does this plugin work on WPMU / WP3+ Multi Site mode? =
Yep. You can install it in /plugins/ and activate it *site-by-site* or *network wide*. Or you can opload it to /mu-plugins/ for automatic (Must-use) inclusion.

== Upgrade Notice ==

= 0.1 =
First concept.

== Changelog ==

= 0.1 =
First concept: replace function wp_notify_moderator()
