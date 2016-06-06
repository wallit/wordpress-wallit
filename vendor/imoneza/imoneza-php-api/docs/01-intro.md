# iMoneza PHP API Documentation

## Introduction

The iMoneza PHP API allows you to interact with the iMoneza platform with an object-oriented PHP library.  Please make 
sure to get your Access Key/Secret and Management Key/Secret from your [iMoneza Merchant Account](https://manageui.imoneza.com).

## Methodology of Use

This library is architected in such a way that is very modular and object-based.  The general workflow is as follows:

1. Create an Options Object  
Create an options object that reflects the type of request you want to do.  If the options object requires additional 
customization or parameters, apply those to the options object before performing your request.

2. Create a Connection Object  
A connection object accepts a number of elements as dependencies of its construction.  The Management API and Access API key
and secrets are required.  Then, an instance of a new `iMoneza\Request\RequestInterface` is required.  A request interface
to Curl is provided by default.  Finally, pass in an instance of a PSR-3 compatible logging interface.  Monolog is recommended, 
but is not required.

3. Call the request method  
The request method of the connection object accepts two arguments: the options object and a data object.  The data object
should be able to accept all of the properties that the API will return.  For convenience, the options object provides a 
method called `getDataObject()` that will return a new instance of the suggested data object.

4. Access the result  
The request method will return the instance of the data object that was specified in its request, but fully populated.

## Error Handling

There are a number of exceptions that are thrown if specific errors happen during the execution.  The base exception
`iMoneza\Exception\iMoneza` will be thrown if an unknown error is not caught.

