============
Custom views
============

************
Presentation
************

The custom views allow each user to have his own monitoring view.
A view may contain 1 to 3 columns. Each column can contain widgets.

A widget is a module allowing certain information to be viewed on certain objects.
It is possible to insert multiple widgets of different types in the same view.
By default, MERETHIS offers widgets allowing to obtain information on: hosts, host groups, services, service groups.
Finally, the last widget allows to view real time performance graphs.

****************
Views Management
****************

All the manipulations below take place in the page entitled **Home** ==> **Custom Views**. This page is also the first page displayed when a user logs into Centreon.

Add view
========

To add a view, click on **Add view**.

.. image :: /images/guide_utilisateur/supervision/01addview.png
   :align: center 

* The field **View name** indicates the name of the view which will be visible by the user
* The field **Layout** allows to choose the number of columns in the view

To change an existing view, click on **Edit view**.

.. note::
    The reduction in the number of columns removes the widgets associated with the column.

Share view
==========

It is possible to share an existing view with one or more users.
To do this, click on **Share view**.

* If the field **Locked** is defined as **Yes**, the other users cannot change the view
* The field **User List** allows to define the users with whom the view is shared
* The field **User Group List** allows to define the user groups with which the view is shared 

Insert widget
=============

To add a widget, click on **Add widget**.

.. image :: /images/guide_utilisateur/supervision/01addwidget.png
   :align: center 

* The field **Widget Title** is used to define a name for our widget
* Choose from the table below the widget type you want to add

Customize widget
================

It is possible to move a widget by drag-and-drop from the title bar.
To reduce a widget, click on |reducewidget|.
By default, the information contained in the widget is refreshed regularly.
To refresh it manually, click on |refreshwidget|.

To customize your widget, click on |configurewidget|.

Delete widget
=============

It is possible to delete the widget by clicking on |deletewidget|.

***************
Widgets Details
***************

The paragraphs below detail the attributes of each widget after clicking on |configurewidget|.

Host widget
===========

Filters
-------

* The field **Host Name Search** can be used to make a search on one or more hostnames
* If the box **Display Up** is checked, the hosts with UP status will be displayed
* If the box **Display Down** is checked, the hosts with DOWN status will be displayed
* If the box **Display Unreachable** is checked, les hôtes en statut UNREACHABLE seront affichés
* The list **Acknowledgement Filter** allows to display the acknowledged or not acknowledged hosts (if the list is empty, the two types of hosts will be displayed)
* The list **Downtime Filter** allows to display the hosts that are subject or not subject to a downtime (if the list is empty, the two types of hosts will be displayed)
* The list **State Type** alllows to display the hosts in SOFT or HARD states (if the list is empty, the two types of hosts will be displayed)
* The list **Hostgroup** allows to display the hosts belonging to a certain host group (if the list is empty, all the hosts will be displayed)
* The list **Results** limits the number of results

Columns
-------

* If the box **Display Host Name** is checked, the host name will be displayed
* If the box **Display Output** is checked, the message associated with the status of the host will be displayed
* The list **Output Length** can be used to limit the length of the message displayed
* If the box **Display Status** is checked, the status of the host is displayed
* If the box **Display IP** is checked, the IP address of the host is displayed
* If the box **Display last Check** is checked, the date and the time of the last check is displayed
* If the box **Display Duration** is checked, the time during which the host has retained its status is displayed
* If the box **Display Hard State Duration** is checked, the time during which the host has retained its HARD state is displayed
* If the box **Display Tries** is checked, the number tries before the validation of the status is displayed
* The list **Order By** allows to classify the hosts in alphabetical order according to multiple settings

Misc
----

* The field **Refresh Interval (seconds)** allows to define the time before data refreshment

Service widget
==============

Filters
-------

* The field **Host Name** can be used to make a search on one or more hostnames
* The field **Service Description** can be used to make a search on one or more service names
* If box **Display Ok** is checked, the services with OK status will be displayed
* If box **Display Warning** is checked, the services with WARNING status will be displayed
* If box **Display Critical** is checked, the services with CRITICAL status will be displayed
* If box **Display Unknown** is checked, the services with UNKNOWN status will be displayed
* If box **Display Pending** is checked, the services with PENDING status will be displayed
* The list **Acknowledgement Filter** allows to display the services acknowledged or not acknowledged (if the list is empty, the two types of hosts will be displayed)
* The list **Downtime Filter** allows to display the services that are subject or not subject to a downtime (if the list is empty, the two types of hosts will be displayed)
* The list **State Type** allows to display the services with SOFT or HARD states (if the list is empty, the two types of hosts will be displayed)
* The list **Hostgroup** allows to display the services belonging hosts belonging to a certain host group (if the list is empty, all the services will be displayed)
* The list **Servicegroup** allows to display the services belonging to a certain service group (if the list is empty, all the services will be displayed)
* The list **Results** limits the number of results

Columns
-------

* If the box **Display Host Name** is checked, the host name will be displayed
* If the box **Display Service Description** is checked, the name of the service will be displayed
* If the box **Display Output** is checked, the message associated with the status of the host will be displayed
* The list **Output Length** can be used to limit the length of the message displayed
* If the box **Display Status** is checked, the status of the host is displayed
* If the box **Display last Check** is checked, the date and the time of the last check is displayed
* If the box **Display Duration** is checked, the time during which the host has retained its status is displayed
* If the box **Display Hard State Duration** is checked, the time during which the host has retained its HARD state is displayed
* If the box **Display Tries** is checked, the number of tries before the validation of the status is displayed
* The list **Order By** allows to classify the services in alphabetical order according to multiple settings

Misc
----

* The field **Refresh Interval (seconds)** allows to define the time before data refreshment

Performance Graph widget
========================

* The field **Service** is used to choose the service for which the graph will be displayed
* The list **Graph period** is used to choose the time period for which the graph will be displayed
* The field **Refresh Interval (seconds)** allows to define the time before data refreshment

Host Group widget
=================

* The field **Hostgroup Name Search** can be used to choose the host groups displayed
* If the box **Enable Detailed Mode** is checked, all the host names and the services associated with these hosts will be displayed for the hostgroups selected
* The list **Results** allows us to limit the number of results
* The list **Order By** is used to classify the service in alphabetical order according to multiple settings
* the field **Refresh Interval (seconds)** allows to define the time before data refreshment

Service Group widget
====================

* The field **Servicegroup Name Search** can be used to choose the service groups displayed
* If the box **Enable Detailed Mode** is checked, all the host names and the services associated with these hosts will be displayed for the service groups selected
* The list **Results** allows us to limit the number of results
* The list **Order By** is used to classify the service in alphabetical order according to multiple settings
* the field **Refresh Interval (seconds)** allows to define the time before data refreshment

.. |deletewidget|    image:: /images/guide_utilisateur/supervision/deletewidget.png
.. |configurewidget|    image:: /images/guide_utilisateur/supervision/configurewidget.png
.. |refreshwidget|    image:: /images/guide_utilisateur/supervision/refreshwidget.png
.. |reducewidget|    image:: /images/guide_utilisateur/supervision/reducewidget.png
