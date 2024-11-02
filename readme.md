## Automatic Block Inserter

This plugin allows you to insert blocks into specific post areas as well as above or below specific block types. This plugin allows you to manage re-usable content in an organized way for your site. 

## Usage

To get started with the Automatic Block Inserter plugin, follow these steps after installation:

### 1️⃣ Creating a New Automatic Block

![CleanShot 2024-05-10 at 21 42 47@2x](https://github.com/smallplugins/automatic-block-inserter/assets/48084051/74098a97-797f-4f15-9e02-bb87c068faec)

1. In your WordPress dashboard, locate and click on the "Automatic Block" menu item.
2. Click on "Add New" to create a new block.

### 2️⃣ Creating the content

You can simply create the block content by adding necessary blocks in the WordPress editor as you normally do for posts/pages.

![CleanShot 2024-05-10 at 21 48 08@2x](https://github.com/smallplugins/automatic-block-inserter/assets/48084051/83a91c93-13c4-4ea6-8931-fbb8e5672d0d)


### 3️⃣ Configuring block position

Once you've created the content for your block, you’ll see a control in the sidebar named **"Position"**. Let's configure our block to be displayed after the post content.

https://github.com/smallplugins/automatic-block-inserter/assets/48084051/892eb768-af8e-4411-8746-f95071d361fd

💡 You can also display the block based on other block types. Here's a quick video showing how to configure your block to display after the **1st** core image block on the post.

https://github.com/smallplugins/automatic-block-inserter/assets/48084051/bbc1fbf4-bc5f-442b-84bc-4ceacdde89c3

## 🛠️ Position control overview 

Here's a quick overview that will help you understand how the positions control works:

1.   **Post Type**: Choose the type of content you want your block to be associated with (e.g., posts, pages).
2.   **Location Type**: Select whether your block should be placed in a specific "post area" or next to a "block type."
3.   **Position**: If you chose "post area," your block could be set to appear within the "post content." For "block type," you’ll get a search-able dropdown menu listing all the available blocks where you can add your new block.
4.   **Location**: Decide if your block should appear **before** or **after** the selected position.
5.   **Offset**: This option is available when you select "**block type**" as your location type. It allows you to specify how many blocks away your new block should be placed from the chosen anchor block.

   
## Changelog

= **1.0.6**

-   **Dev**: Bump freemius SDK.

= **1.0.5**

-   **Improve**: New ui control to select/search available block types.

= **1.0.4**

-   **Refactor**: Added an additional check to avoid rendering outside the loop.

= **1.0.3**

-   **New**: Add support for custom block types.
-   **New**: Add control to restrict the block insertion to top-level blocks.
-   **New**: Add support for offset counting from the bottom.
-   **Fix**: Bugs.

= **1.0.2**

-   **Fix**: Add classic editor support.
-   **New**: Add ability to restrict blocks to custom post type.

= **1.0.1**

-   **Tweak**: Use a more scoped block type filter.

= **1.0.0**

-   **New**: Initial Release
