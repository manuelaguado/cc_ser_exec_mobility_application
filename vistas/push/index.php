Post.php<br>
Activar server: <pre>php bin/push-server.php</pre><br>
Activar server persistente: <pre>nohup php bin/push-server.php</pre><br>
Publicar: <pre>conn.publish('{"title":"titled","article":"articled"}', 'event');</pre> category=event<br>
Verificar puertos abiertos: <pre>nmap -sT -O localhost</pre><br>
Cerrar puertos (Terminar server): <pre>fuser -k 8080/tcp</pre><br>
Documentacion: <pre><a href="http://socketo.me/docs/push" target="_blank">http://socketo.me/docs/push</a></pre><br>
Libreria autobahn: <pre><a href="http://autobahn.ws/" target="_blank">http://autobahn.ws/</a></pre><br>
Verificar comunicacion desde la consola
