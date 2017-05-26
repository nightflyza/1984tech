# 1984tech for Squid 
Конфиг создавался под версию: **Squid Cache: Version 3.5.25**

Squid - выполняет функцию прозрачного прокси,  без кеширования и без подмены сертификата, тем самым не создает MIT аткаку.

При блокировке `HTTP` трафика - клиенту показывается наша страничка с причиной блокировки.

При блокировке  `HTTPS` трафика - Squid просто сбрасывает соединение и браузер показывает сообщение:

    Не удается получить доступ к сайту

Не забываем, что если сайт не запрещен - то клиент выйдет в интернет через **Squid** с IP-адреса, на котором установлен **Squid**.

### Необходимые действия на сервере где установлен Squid (Локальный сервер)
- Редактирием файл `1984tech.ini` с настройками под наши нужды
- Смотрим доступные функции скрипта:

  `# php cli/squidgen --help`

- Запускаем скрипт для генерации конфигов **Squid**:

  `# php cli/squidgen --generate`

- Делаем реконфигурацию конфигов  **Squid** (если он был запущен до этого)

  `# squid -k reconfigure`
  
- Необходимо добавить правила фаервола для заворота клиентов в Squid 
 
  `# ipfw add 1984 fwd 127.0.0.1,3128 ip from not me to to table\(42\) dst-port 80`

  `# ipfw add 1984 fwd 127.0.0.1,3129 ip from not me to to table\(42\) dst-port 443`

- И запустить скрипт генерации IP-адресов для таблицы 42
      
  `# php cli/ipfwgen --generate`
    
- Или же запустить скрипт, который напрямую обновит фаеровл
   
  `# php cli/ipfwgen --tableupdate`

### Необходимые действия на удаленном сервере NAS (без установки Squid)

- Необходимо добавить правила фаервола для заворота клиентов в Squid 
 
  `# ipfw add 1984 fwd SQUID_SERVER_IP ip from LOCALNET to to table\(42\) dst-port 80,443`
  
Вместо **`SQUID_SERVER_IP`** подставляете IP-адрес сервере где установлен **Squid** с прозрачным проксированием

Вместо **`LOCALNET`** подставляете IP-адреса подсети клиентов

- Также на удаленном сервере NAS необходимо выполнить скрипт генерации 42 таблицы фаервола, что-бы не весь трафик заворачивался в **Squid**

    `# php cli/ipfwgen --generate`  или так `# php cli/ipfwgen --tableupdate`
#### Важное замечание по поводу правил на удаленном NAS:
- В правиле фаервола указывать порт на который форвардить - **не нужно**
- Удаленный **NAS** и **Squid** - должны находится в одноранге
- Удаленный **NAS** должен видеть MAC-адрес сервера, где установлен **Squid**, и наоборот **Squid** должен видеть MAC-адрес удаленного **NAS** сервера

*Если кто-то спросит, почему именно так, вот вам ответ:*
````
     fwd | forward ipaddr | tablearg[,port]
             Change the next-hop on matching packets to ipaddr, which can be
             an IP address or a host name.  For IPv4, the next hop can also be
             supplied by the last table looked up for the packet by using the
             tablearg keyword instead of an explicit address.  The search
             terminates if this rule matches.

             If ipaddr is a local address, then matching packets will be
             forwarded to port (or the port number in the packet if one is not
             specified in the rule) on the local machine.
             If ipaddr is not a local address, then the port number (if
             specified) is ignored, and the packet will be forwarded to the
             remote address, using the route as found in the local routing
             table for that IP.
             A fwd rule will not match layer-2 packets (those received on
             ether_input, ether_output, or bridged).
             The fwd action does not change the contents of the packet at all.
             In particular, the destination address remains unmodified, so
             packets forwarded to another system will usually be rejected by
             that system unless there is a matching rule on that system to
             capture them.  For packets forwarded locally, the local address
             of the socket will be set to the original destination address of
             the packet.  This makes the netstat(1) entry look rather weird
             but is intended for use with transparent proxy servers.
````
