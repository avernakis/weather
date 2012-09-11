# Raspberry Pi + pywws = weather station

Here-in are some scripts and templates that I created whilst setting up my automated Raspberry Pi weather station. The hardware behind the scenes can all be purchased "off the shelf," and comprises of no more than:
* Raspberry Pi
* WH1080 Professional Weather Station

The main software that operates behind the scenes is [pywws](http://code.google.com/p/pywws/). I also make use of [SQLite3](http://www.sqlite.org/), [PHP5](http://www.php.net/) and [Lighttpd](http://www.lighttpd.net/). You do not need to be a unix guru to copy my endeavours, though you will need to know some basic principles.

## 1. First Steps

I use a custom build based on a minimal [Raspbian](http://www.raspbian.org/) distribution, however this is not strictly necessary. If you use something other than Raspbian then commands may vary but the principles remain the same.

*When compiling this software do not have X (startx) running, as some people have reported compilation errors as a result.*

You will want to install git and the python-dev packages; to do so type:
* sudo apt-get update
* sudo apt-get install git
* sudo apt-get install python-dev

Once this is done, create a directory for storing the library and software source code...
* cd ~
* mkdir src
* cd src
* wget http://pypi.python.org/packages/source/C/Cython/Cython-0.16.tar.gz#md5=7934186ada3552110aba92062fa88b1c
* wget http://sourceforge.net/projects/libusb/files/libusb-1.0/libusb-1.0.9/libusb-1.0.9.tar.bz2
* git clone https://github.com/gbishop/cython-hidapi.git
* wget http://pywws.googlecode.com/files/pywws-12.05_r521.tar.gz

Now extract the files from their archives;
* tar xvzf Cython-0.16.tar.gz
* tar xvzf pywws-12.05_r521.tar.gz
* tar xvjf libusb-1.0.9.tar.bz2

Now you need to compile the libraries and software in the following manner:
* cd ~/src/Cython-0.16
* sudo python setup.py install
* cd ~/src/libusb-1.0.9
* ./configure
* make
* sudo make install
* cd ~/src/cython-hidapi
* nano setup.py

Change the following line:
*os.environ['CFLAGS'] = "-I/usr/include/libusb-1.0"*
to:
*os.environ['CFLAGS'] = "-I/usr/local/include/libusb-1.0"*

* sudo python setup.py install

If at any point (and in particular the last) you receive an error relating to *udev* then you will need to type these commands before continuing:
* sudo apt-get install udev
* sudo apt-get install libudev0
* sudo apt-get install libudev-dev

Finally, assuming you have connected the WH1080 monitor to your Raspberry Pi, you can test that weather station data is obtainable by doing the following:
* cd ~/src/pywws-12.05_r521
* sudo python TestWeatherStation.py



Many thanks to pingu512 from the Raspberry Pi forums for providing much of the above information. The next step is of course to customise, log, and display data according to your needs. In this repository you will find the custom templates, scripts, and database schema that I am using.
