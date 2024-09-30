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
        stage('Build') {
            steps {
                script {
                    // Exécuter les commandes pour construire le projet
                    sh '''
                    # Installer les dépendances PHP
                    composer install

                    # Installer les dépendances JavaScript
                    npm install
                    '''
                }
            }
        }


        stage('SonarQube Analysis') {
            steps {
                // Exécuter SonarScanner pour analyser le code
               script {
                    def scannerPath = 'C:\\sonar-scanner\\sonar-scanner-6.1.0.4477-windows-x64\\bin\\sonar-scanner.bat'
                    bat "${scannerPath} -Dsonar.projectKey=Analyze-code -Dsonar.sources=. -Dsonar.host.url=http://localhost:9000 -Dsonar.login=admin -Dsonar.password=@CharroudAfaf12@"
               }
            }
        }
         stage('Trivy Scan') {
            steps {
                // Scanner d'images Docker avec Trivy
                sh '''
                trivy image ${DOCKER_IMAGE}
                '''
            }
        }
        stage('Docker Build') {
            steps {
                script {
                    // Construire l'image Docker
                    sh '''
                    docker build -t ${DOCKER_IMAGE} .
                    '''
                }
            }
        }
         stage('Push Docker Image') {
            steps {
                script {
                    // Pousser l'image Docker vers Docker Hub
                    sh '''
                    docker push ${DOCKER_IMAGE}
                    '''
                }
            }
        }

        stage('Deploy with ArgoCD') {
            steps {
                script {
                    // Déployer l'application avec ArgoCD
                    sh '''
                    argocd app sync task-manager
                    '''
                }
            }
        }
        stage('Operate with Kubernetes') {
            steps {
                script {
                    // Opérations avec Kubernetes
                    sh '''
                    kubectl --kubeconfig=${KUBE_CONFIG} get pods
                    kubectl --kubeconfig=${KUBE_CONFIG} get services
                    '''
                }
            }
        }
        stage('Prometheus and Grafana Monitoring') {
            steps {
                script {
                    // Optionnel: Ajouter des vérifications de monitoring avec Prometheus et Grafana
                    // Pour des tâches spécifiques, consultez l'API ou les dashboards Grafana.
                }
            }
        }
    }
     post {
        success {
            echo 'Pipeline succeeded!'
        }
        failure {
            echo 'Pipeline failed!'
        }
    }
}
