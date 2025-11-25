# Get branch info
export GIT_LOCAL_BRANCH ?= $(shell git rev-parse --abbrev-ref HEAD)
export APP_NAME := $(or $(PROJECT_NAME), pdex)
export BUILD_ID := $(or $(BUILD_ID), 1)
export BUILD_REF := $(or $(BUILD_REF), $(GIT_LOCAL_BRANCH))
export ACR_NAME := acrpdexdev
export AKS_RG := rg-pdex-dev-central-ca
export AKS_CLUSTER := aks-pdex-dev
export IMAGE_TAG := $(or $(IMAGE_TAG), $(GIT_LOCAL_BRANCH))

ifeq ($(GIT_LOCAL_BRANCH),dev)
define DEPLOY_FILE
"k8s/dev-deployment.yaml"
endef
define PARAM_FILE
"dev.env"
endef
define BUILD_TAG
"latest-dev"
endef

else ifeq ($(GIT_LOCAL_BRANCH),test)
define DEPLOY_FILE
"k8s/test-deployment.yaml"
endef
define PARAM_FILE
"test.env"
endef
define BUILD_TAG
"latest-test"
endef

else
define DEPLOY_FILE
"k8s/prod-deployment.yaml"
endef
define PARAM_FILE
"prod.env"
endef
define BUILD_TAG
"latest-prod"
endef
endif

print-status:
    @echo " +---------------------------------------------------------+ "
    @echo " | PROJECT: $(APP_NAME)"
    @echo " | BRANCH: $(BUILD_REF)"
    @echo " | IMAGE TAG: $(BUILD_TAG)"
    @echo " | DEPLOY FILE: $(DEPLOY_FILE)"
    @echo " +---------------------------------------------------------+ "

# Build and push Docker image to ACR
build-image:
    @echo "Building Docker image..."
    docker build -t $(ACR_NAME).azurecr.io/$(APP_NAME):$(BUILD_TAG) .
    az acr login --name $(ACR_NAME)
    docker push $(ACR_NAME).azurecr.io/$(APP_NAME):$(BUILD_TAG)

# Deploy to AKS
deploy:
    @echo "Deploying to AKS..."
    az aks get-credentials --resource-group $(AKS_RG) --name $(AKS_CLUSTER) --overwrite-existing
    kubectl apply -f $(DEPLOY_FILE)
    kubectl rollout status deployment/$(APP_NAME)