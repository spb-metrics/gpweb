<!--
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
-->

<p>
A limita��o no acesso � servidor remoto n�o � por parte do PHP, mas sim, uma pr�-configura��o do pr�prio MySQL, que tem por objetivo deixar o daemon mais seguro enquanto gerencia as conex�es na mem�ria do servidor. Na realidade o MySQL vem configurado por default para n�o aceitar conex�es de fora do pr�prio servidor onde ele est� instalado.
</p><p>
Isso significa dizer que voc�, por default, n�o pode separar a sua aplica��o do servidor onde o MySQL est� rodando. Contudo, quando se diz �por default�, n�o significa dizer que n�o � poss�vel se alterar!;) � A primeira coisa a se fazer no caso em espec�fico e, em praticamente todos os ambientes (Linux ou Windows) onde o MySQL est� rodando, � alterar o arquivo my.cnf (que no caso do Apache2Triad encontra-se em C:\Windows\my.cnf).
</p><p>
No caso do seu my.cnf conter a linha (geralmente no in�cio do arquivo ou na se��o [mysqld]) SKIP=��skip-networking�, comente-a (basta inserir no in�cio desta linha um �#�� exemplo: #SKIP=��skip-networking�). Essa instru��o anula de forma declarada a possibilidade de conex�es vindas da rede (local ou n�o).
</p><p>
Outra op��o importante, � comentar (caso haja) a linha: <b>bind-address = 127.0.0.1</b>, ou seja, inserir o cercadilha no in�cio. Essa linha, do jeito que est�, acaba resolvendo nomes (tabelas, bases, usu�rios) apenas localmente (127.0.0.1). Como o nosso objetivo � fazer acesso externo, torna-se imprescind�vel comentar a linha. Depois de proceder com as altera��es, reinicie o servi�o no seu sistema operacional (no caso do Windows, inicar executar services.msc)!
</p><p>
Bem, agora que a configura��o do MySQL j� est� admitindo conex�es remotadas, transa��es originadas de um servidor de aplica��es (onde est� o seu sistema/site php), chega a hora de definirmos que usu�rio ser� aceito durante as conex�es remotas; pra que base/tabela de dados e de que endere�o (n�mero IP, por exemplo) elas vir�o.
</p><p>
Conectando remotamente no servidor MySQL 192.168.1.2 (no exemplo um servidor Windows sendo acessado por um servidor Linux � via Putty)
</p><p>
O primeiro passo � conectar ao SGBD MySQL e a base de dados que queremos acessar remotamente, de prefer�ncia como usu�rio root do MySQL afim de definirmos as permiss�es para acesso � base em quest�o. Neste caso, abra um prompt do MSDOS ou um terminal no Linux e digite:
</p><p>
<b>mysql -D pecm -u root</b>
</p><p>
Note, que neste exemplo, o banco/base de dados chama-se pecm e, estou logando como usu�rio root (n�o do sistema operacional, mas do MySQL (que no caso do Apache2Triad possui a mesma senha). Em seguida:
</p><p>
<b>mysql grant all privileges on pecm.* to root@192.168.160.105 identified by �teste�;</b>
</p><p>
Aqui estou liberando acesso para o usu�rio root, cuja origem do pedido deste acesso venha da m�quina (servidor Apache + PHP) 192.168.160.105, para todas as tabelas da base de dados pecm (pecm.*), cuja senha de acesso requisitada ser� teste. Note que � poss�vel liberar o acesso para qualquer usu�rio v�lido e com permiss�es �teis na respectiva base de dados.
</p><p>
Outra observa��o importante � que todas as mudan�as/implementa��es at� aqui, precisam ser feitas, obviamente, na m�quina 192.168.160.198, ou seja, no servidor onde o MySQL est� rodando e os dados do sistema/site est�o dispon�veis.
</p><p>
� poss�vel em alguns casos, como em algumas distribui��es Linux que o acesso remoto do usu�rio root do MySQL esteja bloqueado. Uma boa dica � criar um usu�rio comum passando a ele as respectivas autoriza��es estruturais � base.
</p>