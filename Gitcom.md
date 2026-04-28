# Xuleta bàsica per programadors (GIT)

## Comandes útils amb `GIT`

- **Crear branca**:
```bash
  git pull origin branca

  $ git checkout -b [name_of_your_new_branch]

  $ git push origin [name_of_your_new_branch]

  $ git branch -a
  ```

- **Merge**:
```bash
  git checkout [branca_objectiu]

  git merge [branca_amb_nous_cambis]

  git branch -d rama-a-borrar-local

  git push origin --delete rama-a-borrar-remoto
  ```