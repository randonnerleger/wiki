function verif_formulaire()
{
 if(document.f2.categories.value == "-Choisissez-")  {
   alert("Veuillez choisir une categorie!");
   document.f2.categories.focus();
   return false;
  }

 if(document.f2.marques.value == "-Choisissez-")  {
   alert("Veuillez choisir une marque!");
   document.f2.marques.focus();
   return false;
  }
  
 if(document.f2.modele.value == "")  {
   alert("Veuillez entrer le nom du modéle!");
   document.f2.modele.focus();
   return false;
  }
 if(document.f2.poids.value == "") {
   alert("Veuillez entrer le poids du matéiel!");
   document.f2.poids.focus();
   return false;
  }
}

function verif_formulaire2()
{
 if(document.f3.newcat.value == "" & document.f3.newmarq.value == "") {
   alert("Veuillez remplir au moins un champ!");
   document.f2.utilisateur.focus();
   return false;
  }
}

