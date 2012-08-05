=== Plugin Name ===
Contributors: SimonFairbairn
Donate link: http://line-in.co.uk/plugins/donate
Tags: comments,custom links,meta box
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.2.3

What if you could get more comments on your blog? 

== Description ==

I know how it goes. You work hard, carefully crafting your posts. You take a stand on a subject you care about and deliver it with the kind of eloquence and poise that would make your English teacher proud. You post it, and then....

...Nothing.

You check your analytics. Everything looks normal. In fact, there was a spike after you posted. It's being shared - people are enjoying it - so then why is no one commenting? 

You try to intellectualise it - you don't need validation. You know in your heart it was a good post, you don't need their say-so. Still, it would be nice if one person - anyone - could give you a little text-based 'atta-boy!'. So how do you get them to do that?

With the NEW Comment Link Suggest-O-Tron! 

This nifty little plugin sits under your post editor and reminds, nay, DEMANDS that you write a little something to push people to publish a little praise on your post. It features a drop down list of suggestions that allow you to ask all the right questions in a matter of seconds and allows you to add your favourite comment prompts to the list! Imagine all of the time that you'll save - perhaps even as much as a minute! 

Features:

* Automatic link generation - sorts out the link to your respond form, and then adds it to the bottom of the post both on your blog and in your RSS feed
* Ability to add some basic styling - make your comment link bold or italic with ease
* Want to go further? The comment is enclosed in its own class for some custom CSS craziness
* Don't have time to think of a question? Choose one of the Suggest-O-Tron prompts and be done in seconds!
* Ask the same question a lot? Add it to the Suggest-O-Tron dropdown and save yourself many seconds of unnecessary typing!
* Draggable meta box - you can drag it up so that it sits right under your post edit field, reminding you to post a prompt

== Installation ==

1. Upload the `comment-link-suggest-o-tron` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the `Plugins` menu in WordPress
1. Go to the `edit posts` page, and down at the bottom should be a new box where you can add your custom text

== Frequently Asked Questions ==

= My text isn't showing as a link! What happened? =

You need to define which areas become the link by using the %-- and --% shorthand. An example would be:

`%--Leave a comment--% and let me know.`

The `Leave a comment` portion would then appear at the bottom of your post as a link.

== Screenshots ==

1. Screenshot of the Custom Comment Text input field

== Changelog ==

= 0.7.0 =
* First release

= 0.7.1 =
* Fixed a bug that meant the field was being emptied if the user edited the post using the `quick edit` functionality

= 0.7.2 =
* Added a few more prompts
* Code cleanup

= 0.8.0 =
* Selecting a drop down option now appends it to current text
* Added the ability to save custom comment text

= 1.1.0 =
* major code revision - now uses Post Meta instead of creating a new DB
* Addition of AJAX for managing the Suggestion Presets
* Delete a suggestion added

= 1.2.0 = 
* Further code revision

= 1.2.2 = 
* Fixed an issue with the <!--more--> tag.

= 1.2.3 = 
* Added ability to have Comment Link Text show at the top of the post.
