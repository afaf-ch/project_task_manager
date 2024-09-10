pipeline {
    agent any

    environment {
        // Définir des variables d'environnement si nécessaire
        SONARQUBE_URL = 'http://localhost:9000'
        SONARQUBE_TOKEN = 'your_sonarqube_token' // Remplacez par votre jeton SonarQube
    }

    stages {
        stage('Checkout') {
            steps {
                // Cloner le dépôt GitHub
                git url: 'https://github.com/afaf-ch/project_task_manager.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                // Installer les dépendances PHP via Composer
                bat 'composer install'
            }
        }

        stage('SonarQube Analysis') {
            when {
                expression { fileExists('sonar-project.properties') }
            }
            steps {
                // Analyse de la qualité du code avec SonarQube
                withSonarQubeEnv('SonarQube') {
                    bat 'sonar-scanner'
                }
            }
        }

        stage('Deploy') {
            steps {
                // Déploiement avec Ansible
                bat 'ansible-playbook -i inventory/hosts deploy.yml'
            }
        }
    }

    post {
        always {
            // Archive les artefacts si nécessaire
            archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true
            
            // Envoyer des notifications par e-mail en cas de succès ou d'échec
            mail to: 'your-email@example.com',
                 subject: "Build ${currentBuild.fullDisplayName}",
                 body: "Build ${currentBuild.fullDisplayName} completed with status: ${currentBuild.currentResult}"
        }
    }
}
