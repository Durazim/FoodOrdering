
    var unF = false;
    var fnF = false;
    var lnF = false;
    var phF = false;
    var emF = false;
    var cpass = false;
    var passF1 = false;
    var passF2 = false;
    var passF3 = false;
    var passF4 = false;
    var passF5 = false;

    function checkUN(un)
    {
        var UNExp = /^[\w.]{3,12}$/i;
        if (UNExp.test(un))
        {
            document.getElementById('unmsg').innerHTML = "Valid Username";
            document.getElementById('unmsg').style = "color:green";
            unF = true;
        }
        else
        {
            document.getElementById('unmsg').innerHTML = "Invalid Username";
            document.getElementById('unmsg').style = "color:red";
            unF = false;
        }
    }

    function checkFN(fn)
    {
        var FNExp = /^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i;
        if (FNExp.test(fn))
        {
            document.getElementById('fnmsg').innerHTML = "";
            fnF = true;
        }
        else
        {
            document.getElementById('fnmsg').innerHTML = "Invalid Name";
            document.getElementById('fnmsg').style = "color:red";
            fnF = false;
        }
    }

    function checkLN(ln)
    {
        var FNExp = /^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i;
        if (FNExp.test(ln))
        {
            document.getElementById('lnmsg').innerHTML = "";
            lnF = true;
        }
        else
        {
            document.getElementById('lnmsg').innerHTML = "Invalid Name";
            document.getElementById('lnmsg').style = "color:red";
            lnF = false;
        }
    }

    function checkEM(em)
    {
        var FNExp = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
        if (FNExp.test(em))
        {
            document.getElementById('emmsg').innerHTML = "Valid Email";
            document.getElementById('emmsg').style = "color:green";
            emF = true;
        }
        else
        {
            document.getElementById('emmsg').innerHTML = "Invalid Email";
            document.getElementById('emmsg').style = "color:red";
            emF = false;
        }
    }


    function checkPH(ph)
    {
        var PHExp = /^((00|\+)973)?\d{8}$/i;
        if (PHExp.test(ph))
        {
            document.getElementById('phmsg').innerHTML = "Valid Phone Number";
            document.getElementById('phmsg').style = "color:green";
            phF = true;
        }
        else
        {
            document.getElementById('phmsg').innerHTML = "Invalid Phone Number";
            document.getElementById('phmsg').style = "color:red";
            phF = false;
        }

    }


    function checkPASS(pass)
    {
        var PASSExp1 = /^[a-zA-Z0-9_.]{8,18}$/;
        if (PASSExp1.test(pass))
        {
            document.getElementById('passmsg1').innerHTML = "Character between 8 to 15<br/>";
            document.getElementById('passmsg1').style = "color:green";
            passF1 = true;
        }
        else
        {
            document.getElementById('passmsg1').innerHTML = "Character between 8 to 15<br/>";
            document.getElementById('passmsg1').style = "color:red";
            passF1 = false;
        }

        var PASSExp2 = /[a-z]/;
        if (PASSExp2.test(pass))
        {
            document.getElementById('passmsg2').innerHTML = "Small latter character<br/>";
            document.getElementById('passmsg2').style = "color:green";
            passF2 = true;
        }
        else
        {
            document.getElementById('passmsg2').innerHTML = "Small latter character<br/>";
            document.getElementById('passmsg2').style = "color:red";
            passF2 = false;
        }

        var PASSExp3 = /[A-Z]/;
        if (PASSExp3.test(pass))
        {
            document.getElementById('passmsg3').innerHTML = "Capital latter character<br/>";
            document.getElementById('passmsg3').style = "color:green";
            passF3 = true;
        }
        else
        {
            document.getElementById('passmsg3').innerHTML = "Capital latter character<br/>";
            document.getElementById('passmsg3').style = "color:red";
            passF3 = false;
        }

        var PASSExp4 = /[0-9]/;
        if (PASSExp4.test(pass))
        {
            document.getElementById('passmsg4').innerHTML = "At least one number<br/>";
            document.getElementById('passmsg4').style = "color:green";
            passF4 = true;
        }
        else
        {
            document.getElementById('passmsg4').innerHTML = "At least one number<br/>";
            document.getElementById('passmsg4').style = "color:red";
            passF4 = false;
        }

        var PASSExp5 = /[_.]/;
        if (PASSExp5.test(pass))
        {
            document.getElementById('passmsg5').innerHTML = "_ or . character<br/>";
            document.getElementById('passmsg5').style = "color:green";
            passF5 = true;
        }
        else
        {
            document.getElementById('passmsg5').innerHTML = "_ or . character<br/>";
            document.getElementById('passmsg5').style = "color:red";
            passF5 = false;
        }
    }

    function checkCPASS(confpass)
    {
        if(confpass==document.getElementById('pass').value)
        {
            document.getElementById('cpassmsg').innerHTML = "Equal";
            document.getElementById('cpassmsg').style = "color:green";
            cpass = true;
        }
        else
        {
            document.getElementById('cpassmsg').innerHTML = " Not equal";
            document.getElementById('cpassmsg').style = "color:red";
            cpass = false;
        }
    }

    function validateMyInputs()
    {
        document.forms[0].JSF.value = 'true';
        document.forms[1].JSF.value = 'true';
        return (unF && fnF && lnF && phF && emF && passF1 && passF2 && passF3 && passF4 && passF5 && cpass);
    }

    function validateMyInputs()
    {
        document.forms[0].JSF.value = 'true';
        return (unF && fnF && lnF && phF && emF && passF1 && passF2 && passF3 && passF4 && passF5 && cpass);
    }
