---
layout: default
title: Deployment
parent: Advanced
---

# Deployment

## Introduction

When you're ready to deploy your Slim application to production, 
there are some important things you can do to make sure your application 
is running as efficiently as possible. 

In this document, we'll cover some great starting points for 
making sure your Slim application is deployed properly.

## Optimization

### Autoloader Optimization

When deploying to production, make sure that you are optimizing Composer's 
class autoloader map so Composer can quickly find the proper 
file to load for a given class:

```
composer install --optimize-autoloader --no-dev
```

In addition to optimizing the autoloader, 
you should always be sure to include a `composer.lock` file in 
your project's source control repository. 
Your project's dependencies can be installed much faster 
when a `composer.lock` file is present.

### Optimizing Configuration Loading

When deploying your application to production, you should make sure that you
enable caching to improve the performance. 

This process includes the caching of the routes and the html templates.

### Deploying With GitHub Actions

[GitHub Actions](https://github.com/features/actions) offers a great
way to build and deploy artifacts to your production servers
on various infrastructure providers such as DigitalOcean, 
Linode, AWS, and more.

If you prefer to build and deploy your applications on your
own machine or infrastructure, you may also 
try [Apache Ant](https://ant.apache.org/) or [Deployer](https://deployer.org/).

### Deploying on Heroku

The [Slim4 skeleton heroku](https://github.com/peter279k/slim4-skeleton-heroku) fork 
shows an example to be friendly for Heroku deployment.
Especially the [deployment.yml](https://github.com/peter279k/slim4-skeleton-heroku/blob/master/.github/workflows/deployment.yml)
file shows how to build and deploy the application.
The demonstration URL is available [here](https://slim4-skeleton-app.herokuapp.com/).

## Read more

* [Slim 4 - Apache Ant](https://ko-fi.com/s/e592c10b5f) (eBook Vol. 2)
