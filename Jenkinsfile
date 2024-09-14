pipeline {
    agent any

    environment {
        // Définir des variables d'environnement si nécessaire
        SONARQUBE_URL = 'http://localhost:9000'
        SONARQUBE_TOKEN = credentials('sonar-token')// Remplacez par votre jeton SonarQube
    }

    stages {
        stage('Checkout') {
            steps {
                // Cloner le dépôt GitHub
                git credentialsId: 'GitHub', url: 'https://github.com/afaf-ch/project_task_manager.git'           }
        }

        stage('SonarQube Analysis') {
            steps {
                // Exécuter SonarScanner pour analyser le code
               script {
                    def scannerPath = 'C:\\sonar-scanner\\sonar-scanner-6.1.0.4477-windows-x64\\bin\\sonar-scanner.bat'
                    bat "${scannerPath} -Dsonar.projectKey=Analyze-code -Dsonar.sources=."
                }
            }
        }

        //stage('Deploy') {
            //steps {
                // Déploiement avec Ansible
              //  bat 'wsl ansible-playbook -i /mnt/c/chemin/vers/inventory/hosts /mnt/c/chemin/vers/deploy.yml'
           // }
        //}
    }

    //post {
      //  always {
            // Archive les artefacts si nécessaire
           // archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true
            
            // Envoyer des notifications par e-mail en cas de succès ou d'échec
           // mail to: 'afafcharroud8@example.com',
              //   subject: "Build ${currentBuild.fullDisplayName}",
               //  body: "Build ${currentBuild.fullDisplayName} completed with status: ${currentBuild.currentResult}"
      //  }
    //}
}
