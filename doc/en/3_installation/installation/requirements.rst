.. _firststepsces3:

=========
Using CES
=========

Please follow our recommandations in order to evoid performance problems. 

*************
Prerequisites
*************

The table below gives the prerequisites for the installation of CES 3.2:

+------------------------+--------------------------+----------------+---------------+
|  Number of Services    |  Number of pollers       | Central        | Poller        |
+========================+==========================+================+===============+
|        < 500           |        1 central         |  1 vCPU / 1 Go |               |
+------------------------+--------------------------+----------------+---------------+
|       500 - 2000       |        1 central         |  2 vCPU / 2 Go |               |
+------------------------+--------------------------+----------------+---------------+
|      2000 - 10000      |  1 central + 1 poller    |  4 vCPU / 4 Go | 1 vCPU / 2 Go |
+------------------------+--------------------------+----------------+---------------+
|      10000 - 20000     |  1 central + 1 poller    |  4 vCPU / 8 Go | 2 vCPU / 2 Go |
+------------------------+--------------------------+----------------+---------------+
|      20000 - 50000     |  1 central + 2 pollers   |  4 vCPU / 8 Go | 4 vCPU / 2 Go |
+------------------------+--------------------------+----------------+---------------+
|      50000 - 100000    |  1 central + 3 pollers   |  4 vCPU / 8 Go | 4 vCPU / 2 Go |
+------------------------+--------------------------+----------------+---------------+

.. note::

 This information is based on the assumption that all optimisations have been performed for Centreon Engine, that the transactions have been enabled in Centreon Broker and that the optimisations have been performed for MariaDB.

 If not virtualized architecture, a minimum CPU frequency of 2.5 GHz is recommended.

 Central server load depends on the number of simultaneous users, the content of pages visited and the refresh rate of the pages. Our studies were performed with 15 simultaneous users on the monitoring page and with 60 s of cooling. 

************
Installation
************


Step 1 : Start
==============

To install, start your server on the support (created from the ISO file) of the Centreon Enterprise Server.
Start with the **Install or upgrade an existing system** option

.. image :: /images/user/abootmenu.png
   :align: center
   :scale: 65%

Click on **Next**

.. image :: /images/user/adisplayicon.png
   :align: center
   :scale: 65%


Step 2 : Choice of language
===========================

Choose your language and click on **Next**.

.. image :: /images/user/ainstalllanguage.png
   :align: center
   :scale: 65%

Select the keyboard used by your system and click on **Next**.

.. image :: /images/user/akeyboard.png
   :align: center
   :scale: 65%


Step 3 : General configuration
==============================

Depending on the type of storage required, choose the options necessary to obtain the partitioning that suits you best.

.. image :: /images/user/adatastore1.png
   :align: center
   :scale: 65%
   
A warning message may appear

.. image :: /images/user/adatastore2.png
   :align: center
   :scale: 65%

Choose your hostname and click on **Configure network** in order to modify your network card configuration.

Select the network card that you want to use and go into "IPv4 Settings" or "IPv6 Settings" tab (depending on the requirement) to configure the IP address of the interfaces. Click on **Apply** to save the changes.

.. image :: /images/user/anetworkconfig.png
   :align: center
   :scale: 65%

Click on **Close** and  **Next** to continue.

Select your time zone and click on **Next**.

.. image :: /images/user/afuseauhoraire.png
   :align: center
   :scale: 65%

Enter the desired root password, and click on **Next**.

Select the partitioning options that suit you best. Then validate.

.. image :: /images/user/apartitionning.png
   :align: center
   :scale: 65%

Step 4 : Component selection
============================

Choose the server type
----------------------

It is possible to choose different options in answer to the question: **Which server type would you like to install?**:


.. image :: /images/user/aservertoinstall.png
   :align: center
   :scale: 65%

|

*	Central server with database : Install Centreon (web interface and database), monitoring engine and broker
*	Central server without database : Install Centreon (web interface only), monitoring engine and broker
*	Poller server : Install poller (monitoring engine and broker only)
*	Database server : Install database server (use with **Central server without database** option)

In our box, we shall choose the **Centreon Server with database** option.

Once all these options have been selected, the installation starts.

.. image :: /images/user/arpminstall.png
   :align: center
   :scale: 65%

When the installation is finished, click on **Restart**.

.. image :: /images/user/arestartserver.png
   :align: center
   :scale: 65%

*************
Configuration
*************

.. _installation_web_ces:

Via the web interface
=====================

Log into web interface via : http://[SERVER_IP]/centreon.

The End of installation wizard of Centreon is displayed, click on **Next**.

.. image :: /images/user/acentreonwelcome.png
   :align: center
   :scale: 65%

The End of installation wizard of Centreon checks the availability of the modules, click on **Next**.

.. image :: /images/user/acentreoncheckmodules.png
   :align: center
   :scale: 65%

Choose the **centreon-engine** option. 

.. image :: /images/user/amonitoringengine1.png
   :align: center
   :scale: 65%

Click on **Next**.

.. image :: /images/user/amonitoringengine2.png
   :align: center
   :scale: 65%

For the choice of broker, choose **Centreon-broker**.

.. image :: /images/user/abrokerinfo1.png
   :align: center
   :scale: 65%

Click on **Next**.

.. image :: /images/user/abrokerinfo2.png
   :align: center
   :scale: 65%

Define the data concerning the admin user, click on **Next**.

.. image :: /images/user/aadmininfo.png
   :align: center
   :scale: 65%

By default, the ‘localhost’ server is defined and the root password is empty. If you use a remote database server, these two data entries must be changed. In our box, we only need to define a password for the user accessing the Centreon databases, i.e. ‘Centreon’, click on **Next**.

.. image :: /images/user/adbinfo.png
   :align: center
   :scale: 65%

If the following error message appears: **Add innodb_file_per_table=1 in my.cnf file under the [mysqld] section and restart MySQL Server.** Perform the following operation:

1.	Log-on to the ‘root’ user on your server
2.	Modify this file 

::

	/etc/my.cnf

3.	Add these lines to the file

.. raw:: latex 

        \begin{lstlisting}
	[mysqld] 
	innodb_file_per_table=1
        \end{lstlisting}

4.	Restart mysql service

.. raw:: latex

        \begin{lstlisting}
	/etc/init.d/mysql restart
        \end{lstlisting}

5.	click on **Refresh**

The End of installation wizard configures the databases, click on **Next**.

.. image :: /images/user/adbconf.png
   :align: center
   :scale: 65%

The installation is finished, click on Finish.

.. image :: /images/user/aendinstall.png
   :align: center
   :scale: 65%

You can now log in.

.. image :: /images/user/aconnection.png
   :align: center
   :scale: 65%

Start monitoring
================

To start monitoring engine :
 
 1.	On web interface, go to **Configuration** ==> **Pollers**
 2.	Select your unique poller, the "Action" button appear
 3.	Select "Apply configuration"
 4.	Generate the configuration. Automaticaly, a config check is done by Centreon Engine
 5.   Clic **Move Files** 
 6.   Now clic **Restart Monitoring Engine**


Introduction to the web interface
=================================


Centreon web interface is made up of several menus, each menu has a specific function:

.. image :: /images/user/amenu.png
   :align: center

|

*       The **Home** menu enables access to the first home screen after logging in. It summarises the general status of the supervision.
*       The **Monitoring** menu contains the status of all the supervised elements in real and delayed time via the viewing of logs.
*       The **Views** menu serves to view and configure the performance graphics for each element in the  information system.
*       The **Reporting** menu serves to view, intuitively (via diagrams), the evolution of the supervision on a given period.
*	The **Configuration** menu serves to configure all monitored objects and the supervision infrastructure.
*       The **Administration** menu serves to configure the Centreon web interface and to view the general status of the servers.

Before going further
====================

it is necessary update the CES 3.2 server. To do this:

 #.	Log in as a ‘root’ on the central server
 #.	Enter this command

::

    yum -y update

Allow the update to run fully and then restart the server in case of a kernel update.

Start your configuration by clicking `here<configuration_start>>`.