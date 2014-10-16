============================
Monitoring Engine Statistics
============================

The Centreon interface offers the user the possibility of viewing the statistics of all the schedulers and those linked to the broker.

***********************
Performance Information
***********************

To view the performance information of your scheduler:

#. Go to the menu **Home** ==> **Monitoring Engine Statistics**
#. In the left menu, click on **Performance Info**
#. Choose your scheduler in the drop-down list **Poller**

.. image :: /images/guide_utilisateur/supervision/03statsordonnanceur.png
   :align: center 

Multiple tables allows to view the performance of your schedulers:

* The table **Actively Checked** can be used to view the number of hosts and services checked in the last minute, the last five minutes, the last quarter of an hour or the last hour.
* The table **Check Latency** can be used to view the minimum, maximum and average latency time of the checks performed on the hosts and the services.
.. warning::
    The longer the latency time, the later are the checks in relation to the initial time programmed by the scheduler. This requires a high load potential by the server.

* The table **Buffer Usage** can be used to view the number of external commands awaiting processing by the scheduler.

.. warning::
    in the case of a passive monitoring injecting many external commands to the scheduler, it is necessary to check this value. If it is too close to the size limit it is possible to lose commands; consequently it is necessary to increase the size of the buffer.

* The table **Status** gives a brief view of the statuses of the hosts and services
* The table **Check Execution Time** can be used to view the execution time of a probe, i.e. the time between when it is started and the moment when it transmits the information to the scheduler.

.. warning::
    The longer the execution time, the more it is detrimental to the execution of the other processes in the queue and the more it generates of the latency. The plugins must be efficient not to engender latency.

*****************
Broker Statistics
*****************

To view the statistics of Centreon Broker:

#. Go to the menu **Home** ==> **Monitoring Engine Statistics**
#. In the left menu, click on **Broker Statistics**
#. Choose your poller in the list entitled **Poller**

.. image :: /images/guide_utilisateur/supervision/03statsbroker.png
   :align: center 

The performance of Centreon Broker is classified by entities of Centreon Broker (module scheduler, Broker-RRD, Broker-Central).

For each entity, the Centreon web interface displays :

* The list of loaded Centreo Broker modules
* The input/output performance

Input/Output Performance
========================

Each performance contains multiple data :

.. image :: /images/guide_utilisateur/supervision/03brokerperf.png
   :align: center 

* The field **State** contains the status of the input, of the output and the status of the module itself
* The field **Temporary recovery mode** indicates if the buffer file of the module is in use
* The field **Last event at** indicates the date and the time of the last event to have occurred
* The field **Event processing speed** indicates the number of events processed per second
* The field **Last connection attempt** contains the date and the time of the last connection attempt
* The field **Last connection success** contains the date and the time of the last successful connection
* The field **Peers** describes the entities connected
* The field **One peer retention mode** indicates the enabling or not of the unidirectional connection mode between the Centreon server and the poller
* The field **Queued events** indicates the number of events to be processed
* The field **File being read** indicates the failover file in the progress of being read
* The field **Reading position (offset)** indicates the reading position associated with the failover file
* The field **File being write** indicates the failover file in the progress of being write
* The field **Write position (offset)** indicates the writing position associated with the failover file
* The field **Max file size** indicates the maximum size of the failover file
* The field **Failover** indicates the associated temporary backup file


******
Graphs
******

It is also possible to view the performance of monitoring engines as performance graphs.
For this :

#. Go to the menu **Home** ==> **Monitoring Engine Statistics**
#. In the left menu, click on **Graphs**
#. Choose your poller in the list entitled **Poller**
#. Choose the period on which you want to view the performance graphs

.. image :: /images/guide_utilisateur/supervision/03graphperf.png
   :align: center 
