Calendar._DN = new Array
("Domingo",
 "Segunda",
 "Terca",
 "Quarta",
 "Quinta",
 "Sexta",
 "Sabado",
 "Domingo");

Calendar._SDN = new Array
("Dom",
 "Seg",
 "Ter",
 "Qua",
 "Qui",
 "Sex",
 "Sab",
 "Dom");

Calendar._MN = new Array
("Janeiro",
 "Fevereiro",
 "Marco",
 "Abril",
 "Maio",
 "Junho",
 "Julho",
 "Agosto",
 "Setembro",
 "Outubro",
 "Novembro",
 "Dezembro");

Calendar._SMN = new Array
("Jan",
 "Fev",
 "Mar",
 "Abr",
 "Mai",
 "Jun",
 "Jul",
 "Ago",
 "Set",
 "Out",
 "Nov",
 "Dez");

Calendar._TT = {};
Calendar._TT["INFO"] = "Sobre o calend�rio";

Calendar._TT["ABOUT"] =
"Sele��o de data:\n" +
"- Use os bot�es \xab, \xbb para selecionar o ano\n" +
"- Use os bot�es " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " para selecionar o m�s\n" +
"- Segure o bot�o do mouse em qualquer um desses bot�es para sele��o r�pida.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Sele��o de hora:\n" +
"- Clique em qualquer parte da hora para incrementar\n" +
"- ou Shift-click para decrementar\n" +
"- ou clique e segure para sele��o r�pida.";

Calendar._TT["PREV_YEAR"] = "Ano ant. (clique para menu)";
Calendar._TT["PREV_MONTH"] = "M�s ant. (clique para menu)";
Calendar._TT["GO_TODAY"] = "Hoje";
Calendar._TT["NEXT_MONTH"] = "Prox. m�s (clique para menu)";
Calendar._TT["NEXT_YEAR"] = "Prox. ano (clique para menu)";
Calendar._TT["SEL_DATE"] = "Selecione a data";
Calendar._TT["DRAG_TO_MOVE"] = "Arraste para mover";
Calendar._TT["PART_TODAY"] = " (hoje)";

// the following is to inform that "%s" is to be the first dia of week %s will be replaced with the dia name.
Calendar._TT["DAY_FIRST"] = "Mostre %s primeiro";

// This may be locale-dependent.  It specifies the week-end dias, as an array of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1 means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Fechar";
Calendar._TT["TODAY"] = "Hoje";
Calendar._TT["TIME_PART"] = "(Shift)clique ou arraste para alterar";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d/%m/%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e %b";

Calendar._TT["WK"] = "sem";
Calendar._TT["TIME"] = "Hora:";
