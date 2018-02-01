// ** I18N
Calendar._DN = new Array
("Zondag",
 "Maandag",
 "Dinsdag",
 "Woensdag",
 "Donderdag",
 "Vrijdag",
 "Zaterdag",
 "Zondag");

Calendar._SDN_len = 2;

Calendar._MN = new Array
("Januari",
 "Februari",
 "Maart",
 "April",
 "Mei",
 "Juni",
 "Juli",
 "Augustus",
 "September",
 "Oktober",
 "November",
 "December");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Info";

Calendar._TT["ABOUT"] =
"DHTML Datum/Tijd Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +
"Ga voor de meest recente versie naar: http://www.dynarch.com/projects/calendar/\n" +
"Verspreid onder de GNU LGPL. Zie http://gnu.org/licenses/lgpl.html voor details." +
"\n\n" +
"Datum selectie:\n" +
"- Gebruik de \xab \xbb knoppen om een jaar te selecteren\n" +
"- Gebruik de " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " knoppen om een maand te selecteren\n" +
"- Houd de muis ingedrukt op de genoemde knoppen voor een snellere selectie.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Tijd selectie:\n" +
"- Klik op een willekeurig onderdeel van het tijd gedeelte om het te verhogen\n" +
"- of Shift-klik om het te verlagen\n" +
"- of klik en sleep voor een snellere selectie.";

//Calendar._TT["TOGGLE"] = "Selecteer de eerste week-dag";
Calendar._TT["PREV_YEAR"] = "Vorig jaar (ingedrukt voor menu)";
Calendar._TT["PREV_MONTH"] = "Vorige maand (ingedrukt voor menu)";
Calendar._TT["GO_TODAY"] = "Ga naar Vandaag";
Calendar._TT["NEXT_MONTH"] = "Volgende maand (ingedrukt voor menu)";
Calendar._TT["NEXT_YEAR"] = "Volgend jaar (ingedrukt voor menu)";
Calendar._TT["SEL_DATE"] = "Selecteer datum";
Calendar._TT["DRAG_TO_MOVE"] = "Klik en sleep om te verplaatsen";
Calendar._TT["PART_TODAY"] = " (vandaag)";
//Calendar._TT["MON_FIRST"] = "Toon Maandag eerst";
//Calendar._TT["SUN_FIRST"] = "Toon Zondag eerst";

Calendar._TT["DAY_FIRST"] = "Toon %s eerst";

Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Sluiten";
Calendar._TT["TODAY"] = "(vandaag)";
Calendar._TT["TIME_PART"] = "(Shift-)Klik of sleep om de waarde te veranderen";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d-%m-%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e %b %Y";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "Tijd:";

Calendar._SMN_len = 3;
Calendar._FD = 0;


// (C)opyright GM Arts 1997-1999
//  Easter
//  ~~~~~~~~~~~~~~~~
Calendar._Easter = {};
Calendar._Easter["wDay"] = 0;
Calendar._Easter["wMonth"] = 0;
Calendar._Easter["year"] = 0;

Calendar._GetEasters = function(yr) {
    // performs integer division of num/dvsr - eg IntDiv(9,4)=2
    var IntDiv = function(num, dvsr) {
        var negate = false;
        var result = 0;
        if (dvsr == 0)
            return null;
        else {
            if (num * dvsr < 0)
                negate = true;
            if (num < 0)
                num = -num;
            if (dvsr < 0)
                dvsr = -dvsr;
            result = ((num - (num % dvsr)) / dvsr);
            if (negate)
                return -result;
            else
                return result;
        }
    }

    // calculate it
    var EasterWestern = function() {
        var g = 0;
        var c = 0;
        var h = 0;
        var i = 0;
        var j = 0;
        var p = 0;
        g = Calendar._Easter["year"] % 19;
        c = IntDiv(Calendar._Easter["year"], 100);
        h = (c - IntDiv(c, 4) - IntDiv(8 * c + 13, 25) + 19 * g + 15) % 30;
        i = h - IntDiv(h, 28) * (1 - IntDiv(h, 28)
            * IntDiv(29, h + 1) * IntDiv(21 - g, 11));
        j = (Calendar._Easter["year"] + IntDiv(Calendar._Easter["year"], 4) + i + 2 - c + IntDiv(c, 4)) % 7;
        p = i - j + 28;
        Calendar._Easter["wDay"] = p;
        Calendar._Easter["wMonth"] = 4;
        if (p > 31)
            Calendar._Easter["wDay"] = p - 31;
        else
            Calendar._Easter["wMonth"] = 3;
    }

    Calendar._Easter["year"] = parseInt(yr, 10);
    if (isNaN(Calendar._Easter["year"])) Calendar._Easter["year"] = 0;
    Calendar._Easter["wDay"] = 0;
    Calendar._Easter["wMonth"] = 0;

    EasterWestern();

    return new Date(Calendar._Easter["year"], --Calendar._Easter["wMonth"], Calendar._Easter["wDay"]);
}

Calendar._Holidays = function (y) {
    // some vars
    var addDays = function(date, days) {
        return new Date(date.getTime() + days * 86400000);
    }
    var holidays = new Object();
    var easters = Calendar._GetEasters(y);

    // fixed holidays
    holidays[y+"-01-01"] = "Nieuwjaar";
    holidays[y+"-05-01"] = "Feest van de arbeid";
    holidays[y+"-07-21"] = "Nationale feestdag";
    holidays[y+"-08-15"] = "OLV Hemelvaart";
    holidays[y+"-11-01"] = "Allerheiligen";
    holidays[y+"-11-11"] = "Wapenstilstand";
    holidays[y+"-12-25"] = "Kerstmis";

    // holidays based on easter
    holidays[easters.print("%Y-%m-%d")] = "Pasen";
    holidays[addDays(easters, 1).print("%Y-%m-%d")] = "Paasmaandag";
    holidays[addDays(easters, 39).print("%Y-%m-%d")] = "OLH Hemelvaart";
    holidays[addDays(easters, 49).print("%Y-%m-%d")] = "Pinksteren";
    holidays[addDays(easters, 50).print("%Y-%m-%d")] = "Pinkstermaandag";

    return holidays;
}
