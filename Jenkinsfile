pipeline {
    agent any

    environment {
        // D�finir des variables d'environnement si n�cessaire
        SONARQUBE_URL = 'http://localhost:9000'
        SONARQUBE_TOKEN = 'your_sonarqube_token' // Remplacez par votre jeton SonarQube
    }

    stages {
        stage('Checkout') {
            steps {
                // Cloner le d�p�t GitHub
                git url: 'https://github.com/afaf-ch/project_task_manager.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                // Installer les d�pendances PHP via Composer
                bat 'composer install'
            }
        }

        stage('SonarQube Analysis') {
            when {
                expression { fileExists('sonar-project.properties') }
            }
            steps {
                // Analyse de la qualit� du code avec SonarQube
                withSonarQubeEnv('SonarQube') {
                    bat 'sonar-scanner'
                }
            }
        }

        stage('Deploy') {
            steps {
                // D�ploiement avec Ansible
                bat 'ansible-playbook -i inventory/hosts deploy.yml'
            }
        }
    }

    post {
        always {
            // Archive les artefacts si n�cessaire
            archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true
            
            // Envoyer des notifications par e-mail en cas de succ�s ou d'�chec
            mail to: 'your-email@example.com',
                 subject: "Build ${currentBuild.fullDisplayName}",
                 body: "Build ${currentBuild.fullDisplayName} completed with status: ${currentBuild.currentResult}"
        }
    }
}
