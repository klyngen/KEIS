![KEIS](https://github.com/klyngen/KEIS/blob/master/doc/dashboard.png)


# KEIS
System for renting out equipment. System is made to handle a large amount of equipment. And are supposed to be managed by RFID tags

# The origin
I was asked to create a simple system for managing equipment for a media institution at NTNU Gjøvik. My first attempt was to create everything from
scratch. This was a bad idea. Without enough structure, the code base was clumsy and was difficult to maintain. 
This time, I used Laravel as the base and created a frontend with polymer web components. 

This was what I needed to create the maintainable application I wanted to create from the beginning. The original idea is to run this 
application on a raspberry pi with an RFID-reader. When I have fixed the RFID-reading script, the RFID will be sendt through uinput. 
Basically making the reader into a **usb device**

# Backend
The backend is mostly complete. Using the API will give you all the functionality you might want. I use JSON for request/response.

# Frontend
The frontend is as mentioned based on polymer. Creating new GUI-components are quite easy. Replacing/modifying existing components
should be quite painless.

# Contributions wanted
If you like my work, please let me know. I am open to all contributions to this project. I believe it has a great potential. 

# LEGAL WARNING 
Since this system is capable of storing personal information, it is important that you as a user spends time to research rules of your country.
