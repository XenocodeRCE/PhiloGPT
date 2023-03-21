# Générer presque automatiquement des cours en C#

## Explications

La seule chose qui empêche que le processus soit totalement automatisé est la suivante : laisser les champs "profession", "cible" et "description" du cours laisse la porte ouverte à des usages qui ne sont pas nécessairement reservés à l'usage que j'en fais, générer des cours de philo.

Vous pouvez à défaut accepter automatiquement le premier titre renvoyé par OpenAI pour avoir un générateur tout automatique.

Le programme se lance et récupère une description de cours (= thèse d'auteurs) ici → https://lacavernedeplaton.fr/api/getCours.php

Ensuite il demande à OpenAI de générer 10 titres, puis laisse l'utilisateur séléctionner le titre qui lui convient le plus.

Suite à cela le programme demande à OpenAI de générer un plan, puis des sous-parties, puis le contenu de ces sous-parties.

L'exportation des cours se fait au format JSON : on peut facilement le convertir (voir tout en bas) ! ;-)



## Structure du programme

- Cours
  - profession
  - cible 
  - description 
  - titre   
  - parties
    - titre 
    - sous-parties
      - titre
      - contenu 

## Code du programme

### Pour OpenAI

```csharp
using System;
using System.Net;
using System.IO;
using System.Text;
using System.ComponentModel;

public static class OpenAIHelper
{
    public static string StringBetweenTwoString(string str, string startingWord, string endingWord)
    {
        int substringStart = str.IndexOf(startingWord);
        substringStart += startingWord.Length;
        int size = str.IndexOf(endingWord, substringStart) - substringStart;
        return str.Substring(substringStart, size);
    }

    public static string OpenAI(BackgroundWorker bgw, string prompt, int maxToken)
    {
        string result = "";
        try
        {
            string key = "sk-***"; // ajoutez ici votre clef OpenAI API
            prompt = prompt.Replace("\"", "\\");
            prompt = prompt.Replace("'", " ");
            prompt = prompt.Replace(":", " ");
            string[] escapers = { "\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c" };
            string[] replacements = { "\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b" };
            for (int i = 0; i < escapers.Length; i++)
            {
                prompt = prompt.Replace(escapers[i], replacements[i]);
            }
            var request = WebRequest.Create("https://api.openai.com/v1/completions") as HttpWebRequest;
            request.Method = "POST";
            request.ContentType = "application/json";
            request.Headers.Add("Authorization", "Bearer " + key);
            string json = "{\n  \"model\": \"text-davinci-003\",\n  \"prompt\": \"" + prompt + "\",\n  \"temperature\": 0.7,\n  \"max_tokens\": " + maxToken + ",\n  \"top_p\": 1,\n  \"frequency_penalty\": 0,\n  \"presence_penalty\": 0\n}";
            byte[] data = Encoding.UTF8.GetBytes(json);
            request.ContentLength = data.Length;
            using (var stream = request.GetRequestStream())
            {
                stream.Write(data, 0, data.Length);
            }
            request.Timeout = 25000;
            var response = request.GetResponse() as HttpWebResponse;
            using (var reader = new StreamReader(response.GetResponseStream()))
            {
                result = reader.ReadToEnd();
            }
            result = StringBetweenTwoString(result, "\"text\":\"", "\",\"index");
            result = result.Replace("\\n", "");
            return result;
        }
        catch (Exception ex)
        {
          // parfois il arrive que request provoque un 'timeout' à cause des serveurs d'OpenAI, dans ce cas on retourne une chaîne de caractère particulère 
          // qu'on récupère plus tard dans le programme pour refaire un appel à cette méthode avec les mêmes arguments
          
            return "redo";
        }
        
    }
}
```

## Les prompts pour OpenAI

```csharp
prompt = $"Je suis {cours.profession}. Je veux faire un cours pour {cours.cible}. Voici une déscription de mon cours : {cours.description}." +
                $" Je souhaite que tu me génères quelques idées de bons titres accrocheurs pour ce cours. Titre :";
```

```csharp
prompt = $"Je suis {cours.profession}. Je veux faire un cours pour {cours.cible}. Voici une déscription de mon cours : {cours.description}." +
                $" Voici le titre du cours : {cours.titre}. Je veux un plan en cinq parties. Pas de sous-parties. Partie 1 :";
````

```csharp
prompt = $"Je suis {cour.profession}. " +
                $"Je veux faire un cours pour {cour.cible}. " +
                $"Voici une déscription de mon cours : {cour.description}." +
                $" Le titre du cours est '{cour.titre}'. " +
                $"Le plan du cours est le suivant : " +
                $"I] {cour.parties[0].titre} " +
                $"II] {cour.parties[1].titre} " +
                $"III] {cour.parties[2].titre} " +
                $"IV] {cour.parties[3].titre} " +
                $"V] {cour.parties[4].titre} " +
                $"Je veux que tu génères deux sous-parties à la partie 'I] {cour.parties[0].titre}'. " +
                $"Sous-partie 1 :";
```

```csharp
prompt = $"Je suis {cour.profession}. " +
               $"Je veux faire un cours pour {cour.cible}. " +
               $"Voici une déscription de mon cours : {cour.description}." +
               $" Le titre du cours est '{cour.titre}'. " +
               $"Le plan du cours est le suivant : " +
               $"I] {cour.parties[0].titre} " +
               $" - {cour.parties[0].sous_Parties[0].titre}" +
               $" - {cour.parties[0].sous_Parties[1].titre}" +
               $"II] {cour.parties[1].titre} " +
               $" - {cour.parties[1].sous_Parties[0].titre}" +
               $" - {cour.parties[1].sous_Parties[1].titre}" +
               $"III] {cour.parties[2].titre} " +
               $" - {cour.parties[2].sous_Parties[0].titre}" +
               $" - {cour.parties[2].sous_Parties[1].titre}" +
               $"IV] {cour.parties[3].titre} " +
               $" - {cour.parties[3].sous_Parties[0].titre}" +
               $" - {cour.parties[3].sous_Parties[1].titre}" +
               $"V] {cour.parties[4].titre} " +
               $" - {cour.parties[4].sous_Parties[0].titre}" +
               $" - {cour.parties[4].sous_Parties[1].titre}" +
               $"Je veux que tu génères le coutenu de la sous-partie '{sp.titre}'. " +
               $"Contenu :";
```

##  Bonus

### Comment utiliser NewtonsoftJSON 

```csharp
private static string MakeValidFileName(string name)
{
    string invalidChars = System.Text.RegularExpressions.Regex.Escape(new string(System.IO.Path.GetInvalidFileNameChars()));
    string invalidRegStr = string.Format(@"([{0}]*\.+$)|([{0}]+)", invalidChars);

    return System.Text.RegularExpressions.Regex.Replace(name, invalidRegStr, "_");
}

[...]

string sav = JsonConvert.SerializeObject(cour);
System.IO.File.WriteAllText($@"D:\Code\paideiAI\paideiAI\bin\Debug\{MakeValidFileName(cour.titre)}.json", sav);

```

### Exemple de la structure en json:
```json
{
   "profession":"professeur de philosophie au lycée",
   "cible":"des lycéens",
   "description":"Le contrat social ne doit pas être vu comme un pur acte de nécessité, mais déjà comme un acte éthique, qui engage certaines valeurs. À L’intérieur même de notre engagement pour vivre ensemble se pose déjà la question de savoir comment répartir les différents biens sociaux, indépendamment de nos caractéristiques particulières. De ce point de vue, L’idéal de justice n’est aucunement postérieur au contrat social, mais le constitue.",
   "titre":" Comprendre le Contrat Social: Une Répartition des Biens Sociaux",
   "parties":[
      {
         "titre":" Introduction: Définition du contrat social et du bien social.  ",
         "sous_Parties":[
            {
               "titre":" Qu'est-ce que le contrat social?  ",
               "contenu":null
            },
            {
               "titre":"   Qu'est-ce qu'un bien social?",
               "contenu":null
            }
         ]
      }
   ]
}

```

### Tout convertir en un seul fichier avec PowerShell

```powershell

$combinedJson = @()

# Récupère tous les fichiers JSON du dossier courant
Get-ChildItem -Path "D:\Code\paideiAI\paideiAI\bin\Debug\json" -Filter *.json | ForEach-Object {
    # Convertit le contenu de chaque fichier JSON en objet PowerShell
	$jsonContent = Get-Content $_.FullName -Encoding UTF8 | ConvertFrom-Json

    # Ajoute chaque objet au tableau
    $combinedJson += $jsonContent
}

$finalJson = $combinedJson | ConvertTo-Json -Depth 4
# Convertit le tableau en une chaîne JSON unique
$finalJson | ConvertTo-Json | Out-File "D:\Code\paideiAI\paideiAI\bin\Debug\Tout.json"

```

à faire : ajouter une commande pour convertir en table markdown / fichier TSV
