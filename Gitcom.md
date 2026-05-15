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



  Updating a local clone after a branch name changes

After you rename a branch in a repository on GitHub, any collaborator with a local clone of the repository will need to update the clone.

From the local clone of the repository on a computer, run the following commands to update the name of the default branch.
```bash
git branch -m OLD-BRANCH-NAME NEW-BRANCH-NAME
git fetch origin
git branch -u origin/NEW-BRANCH-NAME NEW-BRANCH-NAME
git remote set-head origin -a
 ```
Optionally, run the following command to remove tracking references to the old branch name.
```bash
git remote prune origin
```