
# Lecture: SNMP V1, V2, and specially V3 (17/05/2016)

This is iPython Notebook writen in bash for a interactive lecture to students from the University of Twente. The course was Internet Management and the lecture was entitled "SNMP V1, V2, and specially V3" [17/may/2016]. For more information access https://learnintsec.org/courses/course-v1:UT+1926531A+2015Q4/info or send me an email (j.j.santanna@utwente.nl). 

** DISCLAIMER: all these scripts works only from inside the 130.89.0.0/16 **

In addition to this notebook we also added the snmp agent config file (snmpd.conf) and the pcap file (lecture_snmp.pcap) that we measure different versions of the snmp protocol.

### Declaring Hosts
In a previous assignment students were requested to install an agent SNMP in one of their devices. The configuration MUST include SNMP versions 1 and 2, on the port 2161 and community name "IMM". While the configuration of SNMPv3 MUST include the usage of authentication and privacy protocol.  Bellow are the hosts and the passwords of each student (that posted their information in https://learnintsec.org/courses/course-v1:UT+1926531A+2015Q4/wiki/UT.1926531A.2015Q4/student-snmp-agent-details/ on time).


```bash
jjkester=geoip.jjkester.nl
corellian=corellian.student.utwente.nl
dinas=utwente.dinas.nl
bratto=snmp.bratto.net
bratto2=81.207.85.129
lownfun=imm.lownfun.com
A=77.161.142.169
B=94.213.70.45
C=145.136.76.9
D=77.166.86.83
E=130.89.235.2
F=192.168.1.10
G=82.74.219.112
H=77.175.238.14
I=195.240.176.102
J=82.75.180.42
```

    

Additionaly, I included my own host to make sure that we can have (at least one) functional example =D


```bash
jjsantanna=ddosdb.org
```

    

### Testing Reachability
Before start testing to access the SNMP agents it is IMPORTANT to make sure that the hosts are reachable. Considering the following example:


```bash
ping -c 1 -t 1 $jjsantanna |grep ttl
```

    64 bytes from 130.89.14.205: icmp_seq=0 ttl=63 time=37.432 ms


# Part I: SNMPv1 and SNMPv2
The students were randomly choose to query their own hosts on-the-spot. The others students were either judging or helping others to succeed in the task. 


```bash
snmpwalk -v 2c -c HUMAN $jjsantanna .1.3.6.1.2.1.1.1.0
#This was my example! Note that the community and the port number is different from the asked to students.
```

    SNMPv2-MIB::sysDescr.0 = STRING: Linux furiosa 4.2.0-36-generic #41-Ubuntu SMP Mon Apr 18 15:49:10 UTC 2016 x86_64



```bash
snmpwalk -v 2c -c public $H .1.3.6.1.2.1.1.1.0 
#PROBLEM: The student didn't put the correct community (IMM) and the correct port (2161). 
#These mistake possibly happened because the student copy and paste my example =D
#Besides of that, the host was unreachable using the default port (161) (as we can see in the output bellow). 
```

    Timeout: No Response from 77.175.238.14



```bash
#With help from other students, the previous example was corrected
snmpwalk -v 2c -c IMM $H:2161 .1.3.6.1.2.1.1.1.0
```

    SNMPv2-MIB::sysDescr.0 = STRING: Linux imm 4.1.19-v7+ #858 SMP Tue Mar 15 15:56:00 GMT 2016 armv7l



```bash
snmpget -v 2c -c IMM $A:2161 .1.3.6.1.2.1.1.2.0
```

    SNMPv2-MIB::sysObjectID.0 = OID: NET-SNMP-MIB::netSnmpAgentOIDs.10



```bash
snmpwalk -v 1 -c IMM $I:2161 .1.3.6.1.2.1.1.1.0
```

    SNMPv2-MIB::sysDescr.0 = STRING: Linux raspberrypi 4.1.19-v7+ #858 SMP Tue Mar 15 15:56:00 GMT 2016 armv7l



```bash
snmpget -v 1 -c IMM $lownfun:2161 1.3.6.1.2.1.1.3.0
```

    DISMAN-EVENT-MIB::sysUpTimeInstance = Timeticks: (80207917) 9 days, 6:47:59.17


### It is horrible to remember Object Identifiers (OID). How can you memorize it?
At this point I discuss with the students that is easier to remember names than remember numbers. I invited then to take a look on the following links that shows the the most used MIBs:
- http://www.simpleweb.org/ietf/mibs/modules/html/?category=IETF&module=SNMPv2-MIB
- http://www.simpleweb.org/ietf/mibs/modules/html/?category=IETF&module=HOST-RESOURCES-MIB
- http://www.simpleweb.org/ietf/mibs/modules/html/?category=IETF&module=IF-MIB
- http://www.simpleweb.org/ietf/mibs/modules/html/?category=IETF&module=IP-MIB


```bash
snmpget -v 2c -c IMM $H:2161 .1.3.6.1.2.1.1.sysName.0
# In this case the student changed only one number for the name. It worked and he was happy!
```

    SNMPv2-MIB::sysName.0 = STRING: imm



```bash
snmpwalk -v 2c -c HUMAN $jjsantanna .iso.org.dod.internet.mgmt.mib-2.system.sysName
# In this example I showed that ALL the numbers can be changed for names
```

    SNMPv2-MIB::sysName.0 = STRING: Furiosa


### Let's make it easier?!
At this moment I showed that we can make everything easier by typing the name of the MIB and the name of the object (separated by two colons).


```bash
snmpget -v 2c -c HUMAN $jjsantanna SNMPv2-MIB::sysName.0
```

    SNMPv2-MIB::sysName.0 = STRING: Furiosa



```bash
snmpget -v 2c -c HUMAN $jjsantanna SNMPv2-MIB::sysContact.0
```

    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>



```bash
snmpwalk -v 2c -c SUPERSAYAJIN5 $jjsantanna UCD-SNMP-MIB::prNames
```

    UCD-SNMP-MIB::prNames = No Such Instance currently exists at this OID



```bash
snmpwalk -v 2c -c HUMAN $jjsantanna SNMPv2-MIB::sysLocation
```

    SNMPv2-MIB::sysLocation.0 = STRING: The Netherlands


By the way... I showed that we can request parts of the MIB using the same type of OID call. The students were very happy about!


```bash
snmpwalk -v 2c -c HUMAN $jjsantanna SNMPv2-MIB::system
```

    SNMPv2-MIB::sysDescr.0 = STRING: Linux furiosa 4.2.0-36-generic #41-Ubuntu SMP Mon Apr 18 15:49:10 UTC 2016 x86_64
    SNMPv2-MIB::sysObjectID.0 = OID: NET-SNMP-MIB::netSnmpAgentOIDs.10
    DISMAN-EVENT-MIB::sysUpTimeInstance = Timeticks: (19103) 0:03:11.03
    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>
    SNMPv2-MIB::sysName.0 = STRING: Furiosa
    SNMPv2-MIB::sysLocation.0 = STRING: The Netherlands
    SNMPv2-MIB::sysORLastChange.0 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORID.1 = OID: SNMP-MPD-MIB::snmpMPDCompliance
    SNMPv2-MIB::sysORID.2 = OID: SNMP-USER-BASED-SM-MIB::usmMIBCompliance
    SNMPv2-MIB::sysORID.3 = OID: SNMP-FRAMEWORK-MIB::snmpFrameworkMIBCompliance
    SNMPv2-MIB::sysORID.4 = OID: SNMPv2-MIB::snmpMIB
    SNMPv2-MIB::sysORID.5 = OID: SNMP-VIEW-BASED-ACM-MIB::vacmBasicGroup
    SNMPv2-MIB::sysORID.6 = OID: TCP-MIB::tcpMIB
    SNMPv2-MIB::sysORID.7 = OID: IP-MIB::ip
    SNMPv2-MIB::sysORID.8 = OID: UDP-MIB::udpMIB
    SNMPv2-MIB::sysORID.9 = OID: SNMP-NOTIFICATION-MIB::snmpNotifyFullCompliance
    SNMPv2-MIB::sysORID.10 = OID: NOTIFICATION-LOG-MIB::notificationLogMIB
    SNMPv2-MIB::sysORDescr.1 = STRING: The MIB for Message Processing and Dispatching.
    SNMPv2-MIB::sysORDescr.2 = STRING: The management information definitions for the SNMP User-based Security Model.
    SNMPv2-MIB::sysORDescr.3 = STRING: The SNMP Management Architecture MIB.
    SNMPv2-MIB::sysORDescr.4 = STRING: The MIB module for SNMPv2 entities
    SNMPv2-MIB::sysORDescr.5 = STRING: View-based Access Control Model for SNMP.
    SNMPv2-MIB::sysORDescr.6 = STRING: The MIB module for managing TCP implementations
    SNMPv2-MIB::sysORDescr.7 = STRING: The MIB module for managing IP and ICMP implementations
    SNMPv2-MIB::sysORDescr.8 = STRING: The MIB module for managing UDP implementations
    SNMPv2-MIB::sysORDescr.9 = STRING: The MIB modules for managing SNMP Notification, plus filtering.
    SNMPv2-MIB::sysORDescr.10 = STRING: The MIB module for logging SNMP Notifications.
    SNMPv2-MIB::sysORUpTime.1 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.2 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.3 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.4 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.5 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.6 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.7 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.8 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.9 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.10 = Timeticks: (1) 0:00:00.01
    SNMPv2-MIB::sysORUpTime.10 = No more variables left in this MIB View (It is past the end of the MIB tree)


### SNMPSet, a dangerous SNMP PDU!
We had a discussion about the snmpset and how dangerous it can be (for the objects that have the MaxAccess = "read-write"). The main question was which object can crash an entire system if the values are changed? I gave a simple non-harmfull examples (bellow).


```bash
snmpset -v 2c -c SUPERSAYAJIN $jjsantanna SNMPv2-MIB::sysDescr.0 s "Goku's Machine" #MaxAccess: read-only
snmpset -v 2c -c SUPERSAYAJIN $jjsantanna SNMPv2-MIB::sysName.0 s "Furiosa" #MaxAccess: read-write
snmpset -v 2c -c SUPERSAYAJIN $jjsantanna SNMPv2-MIB::sysLocation.0 s "The Netherlands" #MaxAccess: read-write

#It was expected that the "sysDescr" will have a different behaviour as "sysName" and sysLocation. 
# The former is read-only object while the latter two objects are read-write.
```

    Error in packet.
    Reason: notWritable (That object does not support modification)
    Failed object: SNMPv2-MIB::sysDescr.0
    
    SNMPv2-MIB::sysName.0 = STRING: Furiosa
    SNMPv2-MIB::sysLocation.0 = STRING: The Netherlands



```bash
#Checking if the sysName and sysLocation changed de facto.
snmpget -v 2c -c HUMAN $jjsantanna SNMPv2-MIB::sysName.0
snmpget -v 2c -c HUMAN $jjsantanna SNMPv2-MIB::sysLocation.0
```

    SNMPv2-MIB::sysName.0 = STRING: Furiosa
    SNMPv2-MIB::sysLocation.0 = STRING: The Netherlands


### Config file (snmpd.conf) changing the permission
It is very important to notice that if a value was set in snmpd.conf 


```bash
snmpset -v 2c -c SUPERSAYAJIN $jjsantanna SNMPv2-MIB::sysContact.0 s "Goku <goku@dragonball.com>" #MaxAccess: read-write
```

    Error in packet.
    Reason: notWritable (That object does not support modification)
    Failed object: SNMPv2-MIB::sysContact.0
    


### Community without rights


```bash
snmpset -v 2c -c IMM $H:2161 SNMPv2-MIB::sysName.0 s "blabla" 
# PROBLEM: the community IMM has no rights to write.
```

    Error in packet.
    Reason: noAccess
    Failed object: SNMPv2-MIB::sysName.0
    


# Part II: SNMPv3 and different levels of Security

### A. Demonstring NoauthNoPriv, authNoPriv and authPriv


```bash
snmpwalk -v 3 \
-u mrsatan \
-l NoauthNoPriv \
$jjsantanna \
SNMPv2-MIB::sysContact
```

    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>



```bash
snmpwalk -v 3 \
-u gohan2 \
-l authNoPriv \
-a MD5 -A zvnq37qR46RGZ \
$jjsantanna \
SNMPv2-MIB::sysContact
```

    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>



```bash
snmpwalk -v 3 \
-u goku2 \
-l authPriv \
-a SHA -A hPmOdiAelZp2N \
-x AES -X VuXjKVJH6FvF6 \
$jjsantanna \
SNMPv2-MIB::sysContact
```

    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>


### B. Checking which students configured correctly the agent SNMPv3
I checked all the students that added in https://learnintsec.org/courses/course-v1:UT+1926531A+2015Q4/wiki/UT.1926531A.2015Q4/student-snmp-agent-details/ their host information.


```bash
snmpwalk -v 3 \
-u student \
-l authPriv \
-a SHA -A v3rys3cr3t \
-x AES -X v3rys3cr3t \
$jjkester:2161 \
SNMPv2-MIB::sysContact
```

    SNMPv2-MIB::sysContact.0 = STRING: Jan-Jelle Kester <j.j.kester@student.utwente.nl>



```bash
snmpget -v 3 \
-u IMM \
-l authPriv \
-a SHA -A 62VuuqQDlidhb2MgmirF3M7xpbvbYFHB \
-x AES -X 5avlk2OzfB3AKZQh9o6zVhquqpS3OgNG \
$I:2161 SNMPv2-MIB::sysContact.0
```

    SNMPv2-MIB::sysContact.0 = STRING: Me <me@example.org>



```bash
#A bit more organised with "\" that allows to write in the following line
snmpget -v 3 \
-u intsec \
-l authPriv \
-a SHA -A FgB0aQk4PUMubqtmDDQSD62qDGH33wZkjAaLhpW1NBsi9q7cFxgRGKtT0DnntbXtFfAuMhoTh4IrpbswdNLOaToa3TNzSSFjrL5H \
-x AES -X FgB0aQk4PUMubqtmDDQSD62qDGH33wZkjAaLhpW1NBsi9q7cFxgRGKtT0DnntbXtFfAuMhoTh4IrpbswdNLOaToa3TNzSSFjrL5H \
$corellian:2161 \
SNMPv2-MIB::sysContact.0
```

    SNMPv2-MIB::sysContact.0 = STRING: Olivier van der Toorn <oliviervdtoorn@gmail.com>



```bash
snmpget -v 3 \
-u secint \
-l authPriv \
-a SHA -A kQVM6PhD7bObFu9TnnueqJIbFoxZawlqqH0oN0RVgDcEZdJWJC2jSyeID6aXO9emXG0QpWuQDPRuYViVrod3IODe6EqcynYUo3VG \
-x AES -X kQVM6PhD7bObFu9TnnueqJIbFoxZawlqqH0oN0RVgDcEZdJWJC2jSyeID6aXO9emXG0QpWuQDPRuYViVrod3IODe6EqcynYUo3VG \
$dinas:2161 \
SNMPv2-MIB::sysContact.0
```

    SNMPv2-MIB::sysContact.0 = STRING: Arvid van den Brink <a.b.vandenbrink@student.utwente.nl>



```bash
snmpget -v 3 \
-u IMM \
-l authPriv \
-u IMM \
-a SHA -A A1C3Z9X7E5 \
-x AES -X 3F28XR5AG1 \
$A:2161 \
SNMPv2-MIB::sysContact.0
```

    SNMPv2-MIB::sysContact.0 = STRING: Me <r.siebel@student.utwente.nl>



```bash
snmpget -v 3 \
-u IMM \
-l authPriv \
-a SHA -A 33ae5b14a39214ad3aac9c93b6bda5f4 \
-x AES -X cd12fbe3855656a6906672dad5a6c6d3 \
$bratto2:2161 \
SNMPv2-MIB::sysContact.0
```

    SNMPv2-MIB::sysContact.0 = STRING: t.j.pool@student.utwente.nl



```bash
snmpget -v 3 \
-u immv3 \
-l authPriv \
-a SHA -A fsU6RGCx1GrQB0rNCDpjGqPeFzuYE8JEK3L7CDsbspv52BFmp1RVJuFMjhfgpEJqwXDNwgNKcgUEh2NbqQFcoanPevzobfuoVfae \
-x AES -X fsU6RGCx1GrQB0rNCDpjGqPeFzuYE8JEK3L7CDsbspv52BFmp1RVJuFMjhfgpEJqwXDNwgNKcgUEh2NbqQFcoanPevzobfuoVfae \
$B:2161 \
SNMPv2-MIB::sysContact.0
```

    SNMPv2-MIB::sysContact.0 = STRING: a.p.aalbertsberg@student.utwente.nl



```bash
snmpget -v 3 \
-u user2 \
-l authPriv \
-a SHA -A ARD31gHJAR \
-x AES -X ARD31gHJAR \
$G:2161 \
SNMPv2-MIB::sysContact.0
```

    No log handling enabled - using stderr logging
    snmpget: Authentication failure (incorrect password, community or key)


# Part III: Comparing SNMP versions 1, 2 and 3 (using TCPDUMP)

#### First we start measuring the packets using tcpdum


```bash
tcpdump -w lecture_snmp.pcap
# We run this line in the background
```

    tcpdump: ioctl(SIOCIFCREATE): Operation not permitted


#### Then, we did 5 types of snmpget using different snmp versions, with and without authentication and privacy protocols


```bash
snmpget -v 1 -c HUMAN $jjsantanna SNMPv2-MIB::sysContact.0
snmpget -v 2c -c HUMAN $jjsantanna SNMPv2-MIB::sysContact.0
snmpget -v 3 -u mrsatan -l NoauthNoPriv $jjsantanna SNMPv2-MIB::sysContact.0
snmpget -v 3 -u gohan2 -l authNoPriv -a MD5 -A zvnq37qR46RGZ $jjsantanna SNMPv2-MIB::sysContact.0
snmpget -v 3 -u goku2 -l authPriv -a SHA -A hPmOdiAelZp2N -x AES -X VuXjKVJH6FvF6 $jjsantanna SNMPv2-MIB::sysContact.0
```

    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>
    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>
    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>
    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>
    SNMPv2-MIB::sysContact.0 = STRING: Jair Santanna <j.j.santanna@utwente.nl>


#### NOW, we analyse the request of each (request) packet


```bash
# This packet shows the snmpget v1. We can notice the community "HUMAN" and the PDU "GetRequest" 
# ".1.3.6.1.2.1.1.4.0". Remember that this is enough to access the device.  
tcpdump -r lecture_snmp.pcap -nttX dst port 161 |grep 1463608904.134180
```

    reading from PCAP-NG file lecture_snmpv3.pcap
    1463608904.134180 IP 130.89.108.3.59888 > 130.89.14.205.161:  C=HUMAN GetRequest(28)  .1.3.6.1.2.1.1.4.0



```bash
# This packet shows the snmpget v2c. We can notice the community "HUMAN" and the PDU "GetRequest" 
# ".1.3.6.1.2.1.1.4.0". Remember that, as in the snmpv1, this is enough to access the device.  
tcpdump -r lecture_snmp.pcap -nttX dst port 161 |grep 1463608904.432229
```

    reading from PCAP-NG file lecture_snmpv3.pcap
    1463608904.432229 IP 130.89.108.3.65113 > 130.89.14.205.161:  C=HUMAN GetRequest(28)  .1.3.6.1.2.1.1.4.0



```bash
# This packet shows the snmpget v3 without authentication and without privacy (NoauthNoPriv). 
# We can notice the user "mrsatan" and the PDU "GetRequest" ".1.3.6.1.2.1.1.4.0". 
# Till this point there is not much difference between v1, v2 and v3.
# Therefore, this information is still enough to access the device.  
tcpdump -r lecture_snmp.pcap -nttX dst port 161 |grep 1463608904.732595
```

    reading from PCAP-NG file lecture_snmpv3.pcap
    1463608904.732595 IP 130.89.108.3.63916 > 130.89.14.205.161:  F=r U=mrsatan E= 0x800x000x1F0x880x800x280xD40xCF0x2F0x8F0xD20x3A0x570x000x000x000x00 C= GetRequest(28)  .1.3.6.1.2.1.1.4.0



```bash
# This packet shows the snmpget v3 with authentication and without privacy (authNoPriv). 
# We can notice the user "gohan2" and the PDU "GetRequest" ".1.3.6.1.2.1.1.4.0". 
# NOW the snmpv3 shows the first difference by adding a password for the user.
tcpdump -r lecture_snmp.pcap -nttX dst port 161 |grep 1463608905.124678
```

    reading from PCAP-NG file lecture_snmpv3.pcap
    1463608905.124678 IP 130.89.108.3.65322 > 130.89.14.205.161:  F=ar U=gohan2 E= 0x800x000x1F0x880x800x280xD40xCF0x2F0x8F0xD20x3A0x570x000x000x000x00 C= GetRequest(28)  .1.3.6.1.2.1.1.4.0



```bash
# FINALLY, this packet shows the snmpget v3 with authentication and privacy (authPriv). 
# We can notice the user "goku2" (which needs a password to access the host) 
# In addition the PDU is encrypted. #tumbsupsnmpv3 =D
tcpdump -r lecture_snmp.pcap -nttX dst port 161 |grep 1463608905.849183
```

    reading from PCAP-NG file lecture_snmpv3.pcap
    1463608905.849183 IP 130.89.108.3.65240 > 130.89.14.205.161:  F=apr U=goku2 [!scoped PDU]c2_2a_b6_e2_8f_49_7c_0a_f7_ed_23_80_84_41_dc_44_93_6c_fa_4b_5f_5d_ca_04_0d_18_c5_42_ee_20_2e_88_f5_50_72_fc_81_d0_05_72_61_4e_8b_15_a3_c8_60_00_16_6a_1a_9c_4b


# Part IV: Security
### Spoofing, DDoS Attacks, and Large Scale Port Scan (Shodan.io)

This python script bellow generates a snmpget pretending to be the IP "127.0.0.1", this is called IP spoofing. As consequence the response of 130.89.14.205 will go to the IP "127.0.0.1" instead of the (attack) machine that request (the spoofed request). 

** Disclaimer: the destination IP (130.89.14.205) doesn't accept snmp request from outside 130.89.0.0/16. Therefore, if you are outside it will not work.


```bash
sudo python

from scapy.all import sr1,IP,UDP,SNMP,SNMPget,SNMPvarbind,ASN1_OID

p = IP(src="127.0.0.1",dst="130.89.14.205")/UDP(dport=161)/SNMP(community="HUMAN",PDU=SNMPget(varbindlist=[SNMPvarbind(oid=ASN1_OID(".1.3.6.1.2.1.1.4.0"))]))

sr1(p)
```

An attack usually uses this type of spoofed requests to either reflect the attack against a third party target misusing hundreds of thousands of hosts which have snmpv1 and v2 installed. By the way, it is known that the majority of hosts running snmp v1 and v2 uses the default community "public".

To make everything easier to attackers that want to abuse hosts running snmp, hundreds of thousands are showed in https://www.shodan.io/search?query=snmp. This initiative performs a large scale port scanning in the entire Internet. Therefore anyone can find many other services to be abused.

## Could you please judge my presentation in http://bit.ly/judge_jairs_presentation


# I hope you like. Thanks for your attention!



# Jair Santanna 
<j.j.santanna@utwente.nl>

[http://jairsantanna.com]
