import pandas as pd
import requests
import pymysql
from bs4 import BeautifulSoup as bs
import json
conn = pymysql.connect(
    host='localhost',
    user='root',
    password='',
    db='bdd_paristanbul',
    charset='utf8mb4',
    cursorclass=pymysql.cursors.DictCursor
)

 
with conn.cursor() as cursor:
#------------------------Requete pour recuperer les ventes-----------------------
    try:       
      sqlProduits = "SELECT * from produits" 
      cursor.execute(sqlProduits)
      
      # Fetch 
      produits = cursor.fetchall()
      df_produits = pd.DataFrame.from_records(produits)
      print(len(df_produits))
    finally :
     print("Récupération des produits effectué ")        

#------------------------Rechercche sur internet -----------------------------
for produit in produits:

    nom = produit['nom_produit']  # Accède au champ texte
    url = f"https://www.bing.com/images/search?q={nom.replace(' ', '+')}"
    r= requests.get(url)
    html1=r.text
    soup1=bs(html1,'lxml')
    lst_images = []
    links = soup1.find_all("a", class_="iusc")
    
    for a in links:
        try:
            data = json.loads(a.get("m"))  # lire le JSON 
            img_url = data.get("murl")     # murl --> lien direct de l'image
            if img_url and img_url.endswith(('.png', '.jpg', '.jpeg')):
                lst_images.append(img_url)
        except Exception as e:
            continue
      # Inserer les images dans la table SQL
            print(f"\nProduit : {nom}")
            for i, img in enumerate(lst_images[:2]):  # Affiche max les deux premiers
                print(f"Lien {i + 1} : {img}")
               
        