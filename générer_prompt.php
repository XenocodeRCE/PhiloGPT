<?php
header('Content-Type: text/html; charset=utf-8');

#region "perspectives"
$perspectives = array(
"L'existence humaine et la culture",
"La morale et la politique",
"La connaissance");
#endregion

#region "notions"
$notions = array(
"Art",
"Bonheur",
"Conscience",
"Devoir",
"État",
"Inconscient",
"Justice",
"Langage",
"Liberté",
"Nature",
"Raison",
"Religion",
"Science",
"Technique",
"Temps",
"Travail",
"Vérité");
#endregion

#region "repères"
$repères = array(
"Absolu/relatif",
"Abstrait/concret",
"En acte/en puissance",
"Analyse/synthèse",
"Concept/ image/métaphore",
"Contingent/nécessaire",
"Croire/savoir",
"Essentiel/accidentel",
"Exemple/preuve",
"Expliquer/comprendre",
"En fait/en droit ",
"Formel/matériel ",
"Genre/ espèce/individu",
"Hypothèse/conséquence/conclusion",
"Idéal/réel",
"Identité/égalité/différence",
"Impossible/possible",
"Intuitif/discursif",
"Légal/légitime",
"Médiat/immédiat",
"Objectif/subjectif/intersubjectif",
"Obligation/contrainte",
"Origine/fondement",
"Persuader/convaincre ",
"Principe/cause/fin",
"Public/privé",
"Ressemblance/analogie",
"Théorie/pratique",
"Transcendant/immanent",
"Universel/général/particulier/singulier",
"Vrai/probable/certain");
#endregion

#region "auteurs"
$auteurs = array(
"Les présocratiques",
"Platon",
"Aristote",
"Zhuangzi",
"Épicure",
"Cicéron",
"Lucrèce",
"Sénèque",
"Épictète",
"Marc Aurèle",
"Nāgārjuna",
"Sextus Empiricus",
"Plotin",
"Augustin",
"Avicenne",
"Anselme",
"Averroès",
"Maïmonide",
"Thomas d’Aquin",
"Guillaume d’Occam. ",
"N. Machiavel",
"M. Montaigne (de)",
"F. Bacon",
"T. Hobbes",
"R. Descartes",
"B. Pascal",
"J. Locke",
"B. Spinoza",
"N. Malebranche",
"G. W. Leibniz",
"G. Vico",
"G. Berkeley",
"Montesquieu",
"D. Hume",
"J.-J. Rousseau",
"D. Diderot",
"E. Condillac",
"A. Smith",
"E. Kant",
"J. Bentham. ",
"G.W.H. Hegel",
"A. Schopenhauer",
"A. Comte",
"A.- A. Cournot",
"L. Feuerbach",
"A. Tocqueville",
"J.-S. Mill",
"S. Kierkegaard",
"K. Marx",
"F. Engels",
"W. James",
"F. Nietzsche",
"S. Freud",
"E. Durkheim",
"H. Bergson",
"E. Husserl",
"M. Weber",
"Alain",
"M. Mauss",
"B. Russell",
"K. Jaspers",
"G. Bachelard",
"M. Heidegger",
"L. Wittgenstein",
"W. Benjamin",
"K. Popper",
"V. Jankélévitch",
"H. Jonas",
"R. Aron",
"J.-P. Sartre",
"H. Arendt",
"E. Levinas",
"S. de Beauvoir",
"C. Lévi-Strauss",
"M. Merleau-Ponty",
"S. Weil",
"J. Hersch",
"P. Ricœur",
"E. Anscombe",
"I. Murdoch",
"J. Rawls",
"G. Simondon",
"M. Foucault",
"H. Putnam");
#endregion"

$rnd_auteur = $auteurs[array_rand($auteurs, 1)];
$rnd_repère = $repères[array_rand($repères, 1)];
$rnd_notion = $notions[array_rand($notions, 1)];
$rnd_perspective = $perspectives[array_rand($perspectives, 1)];

$prompt = "
Perspective du programme : $rnd_perspective . <br>
Notion du programme : $rnd_notion . <br>
Auteur : $rnd_auteur . <br>
 <br>
1) Élaborer la pensée de l'auteur sur la notion, en faisant référence à un livre si possible. Développer et argumenter de façon rigoureuse. <br>
 <br>
2) Mobiliser un exemple pertinent. <br>
 <br>
3) Proposer une distinction conceptuelle qui permet de comprendre l'argument de l'auteur. <br>
 <br>
4)  Proposer une analyse de ce qu vient d'être dit en utilisant ce repère du programme : $rnd_repère .  <br>
---<br>
1 :";

echo $prompt;

?>
