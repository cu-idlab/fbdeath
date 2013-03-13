# Zf2mFacebook
=============

Zf2mFacebook provides integration with [Facebook php-sdk](https://github.com/facebook/facebook-php-sdk) for [Zend Framework 2](http://framework.zend.com).

The library has been developed using the original idea from widmogrod's [zf2-facebook-module](https://github.com/widmogrod/zf2-facebook-module)

## Installation

 1. Add `"zf2-modules/zf2m-facebook": "dev-master"` to your `composer.json` file and run `php composer.phar update`.
 2. Add `Zf2mFacebook` to your `config/application.config.php` file under the `modules` key.

## Configuration

Zf2mFacebook uses the same configuration variables as Original Facebook php-sdk.

    `config` - passed directly to the Facebook API class. 
             - Default values are:
				- setAppIdInHeadScript : Default to true, renders Facebook appId variable in head script
				- appId: Default to false, must override this value with your own Facebook appId
				- secret: Default to false, must override this value with your own Facebook secret
    
## Documentation

### Api calls

To acces the library from controller simply call:
	
	$fb = $this->getServiceLocator()->get('FacebookService');
	
Please refer to [Facebook php-sdk](https://github.com/facebook/facebook-php-sdk) for API doc.

