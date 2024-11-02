=== Automatic Block Inserter ===
Contributors: Small Plugins, freemius
Donate link: https://smallplugins.com/
Tags: reusable, block
Requires at least: 5.8.3
Requires PHP: 5.7
Tested up to: 6.7
Stable tag: 1.0.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-1.0.6.html

This plugin allows you to easily insert blocks into specific post areas as well as above or below specific block types.

== Description ==

Automatic Block Inserter is a powerful plugin that allows you to easily insert blocks into specific post areas as well as above or below specific block types based on an offset.

== Installation ==

1. Download the plugin ZIP file.
2. Go to WordPress Dashboard > Plugins > Add New.
3. Upload the ZIP file and activate the plugin.
4. Navigate to any page or post edit screen, and add a Query Loop block. You'll now see the "post" dropdown option, added by the **Query Loop Post Selector** plugin.

== Screenshots ==

1. Plugin banner.
2. You can insert any block using this plugin.
3. You can insert anywhere using the intuitive position control.

== Changelog ==

= 1.0.6 =
* Dev: Bump freemius SDK.

= 1.0.5 =
* Improve: New ui control to select/search available block types.

= 1.0.4 =
* Refactor: Added an additional check to avoid rendering outside the loop.

= 1.0.3 =
* New: Add support for custom block types.
* New: Add support for offset counting from the bottom.
* New: Add control to restrict the block insertion to top-level blocks.
* Fix: Bugs.

= 1.0.2 =
* Fix: Add classic editor support.
* New: Add ability to restrict blocks to custom post type.

= 1.0.1 =
* Tweak: Use a more scoped block type filter.

= 1.0.0 =
* New: Initial Release

== Usage ==

To get started with the Automatic Block Inserter plugin, follow these steps after installation:

**Creating a New Automatic Block:**

1. In your WordPress dashboard, locate and click on the "Automatic Block" menu item.
2. Click on "Add New" to create a new block.
3. Enter the content for your block just like you would for a post or a page.

**Configuring Block Settings:**

Once you've created the content for your block, you’ll see a control in the sidebar named **"Position."**

Here, you can configure where and how your block will be inserted:

-   **Post Type**: Choose the type of content you want your block to be associated with (e.g., posts, pages).
-   **Location Type**: Select whether your block should be placed in a specific "post area" or next to a "block type."
-   **Position**: If you chose "post area," your block could be set to appear within the "post content." For "block type," you’ll get a dropdown menu listing all the available blocks where you can anchor your new block.
-   **Location**: Decide if your block should appear before or after the selected position.
-   **Offset**: This option is available when you select "block type" as your location type. It allows you to specify how many blocks away your new block should be placed from the chosen anchor block.
    Insertion Logic and Display:

The plugin uses the settings you've chosen to determine where to insert the block in your content.
If you select "after" in the "Location" setting for a post area, for example, the block will appear immediately following the post content.
If you set an offset with "block type," the plugin counts the specified number of blocks and places your new block accordingly.
Previewing and Adjusting:

You can preview your post or page to see how the block appears with the rest of your content.
If the placement isn’t quite right, you can go back and adjust the settings as needed until you’re satisfied with the positioning.

**Multiple Blocks and Conditions:**

The plugin allows you to create multiple blocks, each with its own set of conditions and placement settings.
This means you can have different blocks appear in different contexts across your site, offering a high level of customization.