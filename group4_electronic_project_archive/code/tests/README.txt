To run the unit tests, simply go to http://onwardtraffic.com/tests/unit_test.php. This will run all our unit tests. 

Our integration testing can be run by going to http://onwardtraffic.com/tests/integration_test.php. 

Our data collection is run on a Cron Job  (cron_tab.txt) that calls masterCurl.php. We cannot have this being manually refreshed so we duplicated the process and set it up with an identical copied database for testing purposes. To run the test file for the masterCurl.php process, simply go to http://onwardtraffic.com/tests/test_master_curl.php. 

