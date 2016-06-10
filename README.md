# iMoneza WordPress Plugin

Integrate your site with your iMoneza account and begin monetizing your content.

## Description

iMoneza is a digital micro-transaction paywall service. This WordPress plugin allows you to quickly and easily integrate
iMoneza with your site. It will add iMoneza's paywall to your site and allow you to manage your iMoneza resources from
within WordPress.  Please note - an iMoneza account is **required**.

Visit [www.imoneza.com](https://www.imoneza.com) for more information about iMoneza.

## Installation

1. Set up an iMoneza account, create an iMoneza property, and generate a set of API keys.
2. Upload imoneza.zip through the plugins installer in WordPress.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Follow setup wizard.

## Frequently Asked Questions

**What version of PHP is required?**

5.4+

## Developer Notes

If you see a need, please put in a ticket.  Or better yet, fork this and submit your own pull request.

### Development

If you need to work against test or qa, please visit the slug of `imoneza-config`, a hidden menu item.

### Release Methodology

1. Merge all changes back into master.
2. Create a new tag locally and push to remote.
3. Create a `imoneza.zip` file using `git archive` of the project
4. Go to GitHub and create a Release from the tag, attach the `imoneza.zip` file and put changelog items in the release notes.
