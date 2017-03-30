{\rtf1\ansi\ansicpg1252\cocoartf1504\cocoasubrtf760
{\fonttbl\f0\fswiss\fcharset0 Helvetica;}
{\colortbl;\red255\green255\blue255;}
{\*\expandedcolortbl;;}
\margl1440\margr1440\vieww10800\viewh8400\viewkind0
\pard\tx720\tx1440\tx2160\tx2880\tx3600\tx4320\tx5040\tx5760\tx6480\tx7200\tx7920\tx8640\pardirnatural\partightenfactor0

\f0\fs24 \cf0 Our data collection is run on a Cron Job  (cron_tab.txt) that calls masterCurl.php. We cannot have this being manually refreshed so we duplicated the process and set it up with an identical copied database for testing purposes. To run the test file for the masterCurl.php process, simply go to {\field{\*\fldinst{HYPERLINK "http://onwardtraffic.com/php/test_master_curl.php"}}{\fldrslt http://onwardtraffic.com/php/test_master_curl.php}}. \
masterCurl.php and find_startstop.php are files run on Cron Job, we have provided you the other files for demo submission purposes.\
\
masterCurl is the cronjob responsible for receiving new traffic information, and also getting new weather information every hour. Find_startstop.php is a helper function which masterCurl calls. Its purpose is to take the current incident and use it to provide information about the road it occurred on to the database.

We were asked to make note of why we have 2 weeks of usable data. We actually started traffic data collection in the beginning of February, and we have that data in traffic_data. However, as we started developing better solutions for our system, we modified our data collection and created new data tables. As we iteratively updated our database model, we realized that there was a specific piece of data which we needed to provide context to the traffic incidents, which we hadn\'92t been collecting. This required us to reset our data. This reset was 2 weeks ago, which is why we have 2 weeks of functional data.}
