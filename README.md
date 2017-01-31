<p align="center">
  <img src="https://github.com/iMoneza/wordpress-imoneza/blob/master/assets/images/logo-rectangle.jpg?raw=true" alt="iMoneza"/>
</p>

# Wallit WordPress Plugin

Integrate your site with your Wallit account and begin monetizing your content.

## Description

Wallit is the subscription management solution. This WordPress plugin allows you to quickly and easily integrate
Wallit with your site. It will add Wallit's paywall to your site and allow you to manage your Wallit resources from
within WordPress.  Please note - a Wallit account is **required**.

Visit [wallit.io](https://wallit.io) for more information about Wallit.

## Installation

1. Set up a Wallit account, create an Wallit property, and generate a set of API keys.
2. Visit the [latest release link](https://github.com/wallit/wordpress-wallit/releases/latest) and download `imoneza.zip` 
3. Upload imoneza.zip through the plugins installer in WordPress
4. Activate the plugin through the 'Plugins' menu in WordPress.
5. Follow setup wizard.

## Frequently Asked Questions

**What version of PHP is required?**

5.4+

## Developer Notes

If you see a need, please put in a ticket.  Or better yet, fork this and submit your own pull request.

### Development

If you need to work against test or qa, please visit the slug of `imoneza-config`, a hidden menu item.

### Release Methodology

1. Merge all changes back into master.
2. Pick the new release number
3. Change the plugin header to the new release number
4. Create a new tag with that new release number locally and push to remote.
5. Create a `imoneza.zip` file using `git archive` of the project
6. Go to GitHub and create a Release from the tag, attach the `imoneza.zip` file and put changelog items in the release notes.
