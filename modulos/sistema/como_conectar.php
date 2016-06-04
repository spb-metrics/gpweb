<!--
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
-->

<p>
A limitação no acesso à servidor remoto não é por parte do PHP, mas sim, uma pré-configuração do próprio MySQL, que tem por objetivo deixar o daemon mais seguro enquanto gerencia as conexões na memória do servidor. Na realidade o MySQL vem configurado por default para não aceitar conexões de fora do próprio servidor onde ele está instalado.
</p><p>
Isso significa dizer que você, por default, não pode separar a sua aplicação do servidor onde o MySQL está rodando. Contudo, quando se diz “por default”, não significa dizer que não é possível se alterar!;) – A primeira coisa a se fazer no caso em específico e, em praticamente todos os ambientes (Linux ou Windows) onde o MySQL está rodando, é alterar o arquivo my.cnf (que no caso do Apache2Triad encontra-se em C:\Windows\my.cnf).
</p><p>
No caso do seu my.cnf conter a linha (geralmente no início do arquivo ou na seção [mysqld]) SKIP=”–skip-networking”, comente-a (basta inserir no início desta linha um “#”… exemplo: #SKIP=”–skip-networking”). Essa instrução anula de forma declarada a possibilidade de conexões vindas da rede (local ou não).
</p><p>
Outra opção importante, é comentar (caso haja) a linha: <b>bind-address = 127.0.0.1</b>, ou seja, inserir o cercadilha no início. Essa linha, do jeito que está, acaba resolvendo nomes (tabelas, bases, usuários) apenas localmente (127.0.0.1). Como o nosso objetivo é fazer acesso externo, torna-se imprescindível comentar a linha. Depois de proceder com as alterações, reinicie o serviço no seu sistema operacional (no caso do Windows, inicar executar services.msc)!
</p><p>
Bem, agora que a configuração do MySQL já está admitindo conexões remotadas, transações originadas de um servidor de aplicações (onde está o seu sistema/site php), chega a hora de definirmos que usuário será aceito durante as conexões remotas; pra que base/tabela de dados e de que endereço (número IP, por exemplo) elas virão.
</p><p>
Conectando remotamente no servidor MySQL 192.168.1.2 (no exemplo um servidor Windows sendo acessado por um servidor Linux – via Putty)
</p><p>
O primeiro passo é conectar ao SGBD MySQL e a base de dados que queremos acessar remotamente, de preferência como usuário root do MySQL afim de definirmos as permissões para acesso à base em questão. Neste caso, abra um prompt do MSDOS ou um terminal no Linux e digite:
</p><p>
<b>mysql -D pecm -u root</b>
</p><p>
Note, que neste exemplo, o banco/base de dados chama-se pecm e, estou logando como usuário root (não do sistema operacional, mas do MySQL (que no caso do Apache2Triad possui a mesma senha). Em seguida:
</p><p>
<b>mysql grant all privileges on pecm.* to root@192.168.160.105 identified by “teste”;</b>
</p><p>
Aqui estou liberando acesso para o usuário root, cuja origem do pedido deste acesso venha da máquina (servidor Apache + PHP) 192.168.160.105, para todas as tabelas da base de dados pecm (pecm.*), cuja senha de acesso requisitada será teste. Note que é possível liberar o acesso para qualquer usuário válido e com permissões úteis na respectiva base de dados.
</p><p>
Outra observação importante é que todas as mudanças/implementações até aqui, precisam ser feitas, obviamente, na máquina 192.168.160.198, ou seja, no servidor onde o MySQL está rodando e os dados do sistema/site estão disponíveis.
</p><p>
É possível em alguns casos, como em algumas distribuições Linux que o acesso remoto do usuário root do MySQL esteja bloqueado. Uma boa dica é criar um usuário comum passando a ele as respectivas autorizações estruturais à base.
</p>