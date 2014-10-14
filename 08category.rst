==========
Categories
==========

Categories are used to define ACLs on the hosts and the services. The aim is to be able to classify the hosts or the services within a category.

Centreon 2.4 includes a new functionality called “Criticality”. As from version 2.5, the levels of criticality are linked to a category, they have become a type of category. A criticality level is an indicator for defining the criticality of a host or a service. The aim is to be able to handle the problems of hosts or services by order of priority. By this system, it is thus possible to filter the objects in the “Supervision” views by criticality.

.. _hostcategory:

***************
Host categories
***************

To add a category of hosts:

1.      Go into the menu: Configuration ==> Hosts
2.      In the left menu, click on Categories
3.      Click on Add

.. image :: /images/guide_utilisateur/configuration/08hostcategory.png
   :align: center
 
*       The **Host Category Name** and **Alias** fields contain respectively the name and the alias of the category of host.
*       The **Linked hosts** list allows us to add hosts to the category.
*       If a host template is added to **Linked host template** list all the hosts which inherit from this Model belong to this category.
*       The **Severity type** box signifies that the category of hosts has a criticality level.
*       The **Level** and **Icon** fields define a criticality level and an associated icon respectively.
*       The **Status** and **Comment** fields allow us to enable or disable the category of host and to comment on it.

**********************
Categories of services
**********************

To add a category of services:
1.      Go into the menu: Configuration ==> Services
2.      In the left menu, click on Categories
3.      Click on Add
 
*       The Name and Description fields define the name and the description of the category of service.
*       If a Model of service belongs to Linked to models of services all the services belonging to this Model of service are in this category.
*       The Is of criticality type box signifies that the category of service has a criticality level.
*       The Level and icons fields define a criticality level and an associated icon respectively.
*       The Status field allows us to enable or disable the category of services.
Note: for more information refer to the associated chapter covering categories.

