<script setup lang="ts">
  import { ref } from "vue";
  import axios from "axios";
  import btn from '@/components/Button.vue';
import { useRouter } from "vue-router";
;
  
  const file = ref<File | null>(null);
  const dragging = ref(false);
  const uploading = ref(false);
  const message = ref("");
  const messageType = ref("");
  const isModalOpen = ref(false);
  const router = useRouter();
  
  const openModal = () => {
    isModalOpen.value = true;
  };
  
  const closeModal = () => {
    isModalOpen.value = false;
    file.value = null;

  };
  
  const triggerFileInput = () => {
    const fileInput = ref("fileInput").value as HTMLInputElement;
    fileInput.click();
  };
  
  const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
      file.value = target.files[0];
    }
  };
  
  const handleDrop = (event: DragEvent) => {
    dragging.value = false;
    if (event.dataTransfer && event.dataTransfer.files[0]) {
      const droppedFile = event.dataTransfer.files[0];
      if (droppedFile.type === "application/octet-stream" || droppedFile.name.endsWith(".prn")) {
        file.value = droppedFile;
        message.value = "";
      } else {
        message.value = "Por favor, envie apenas arquivos .prn.";
        messageType.value = "alert-danger";
      }
    }
  };
  
  const uploadFile = async (): Promise<void> => {
  if (!file.value) {
    console.error("Nenhum arquivo selecionado.");
    return;
  }

  // Cria o FormData para o envio
  const formData = new FormData();
  formData.append("arquivo", file.value);

  uploading.value = true;

  try {
    // Valida se o arquivo é do tipo File
    if (!(file.value instanceof File)) {
      throw new Error("Arquivo inválido.");
    }

    // Envia o arquivo para o backend
    const response = await axios.post("http://localhost:8081/api/upload", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    // Verifica a resposta do servidor
    if (response.data.success) {
      message.value = response.data.msg;
      messageType.value = "alert-success";
      router.push("/dashboard"); // Redireciona para o dashboard
    } else {
      message.value = response.data.msg;
      messageType.value = "alert-danger";
    }
  } catch (error) {
    console.error(error);
    message.value = "Erro ao enviar o arquivo.";
    messageType.value = "alert-danger";
  } finally {
    uploading.value = false;
    file.value = null;
  }
};



  </script>
  

<template>
    <div class="container mt-5">
      <h1 class="text-center text-warning">Upload de Arquivo PRN</h1>
      <p class="welcome-message text-center">
            Bem-vindo ao sistema de processamento de movimentações de conta corrente!<br> 
            Desenvolvemos esta ferramenta para otimizar a análise das movimentações financeiras dos associados da Sicredi Pioneira RS,<br>
            transformando dados brutos em informações estratégicas, fundamentais para o crescimento contínuo e a evolução do negócio.<br><br>
            Nesta página, você tem a possibilidade de carregar um arquivo bruto contendo dados financeiros. Através de um processo de <strong>ETL (Extração, Transformação e Carga)</strong>, o sistema irá extrair as informações relevantes, realizar a transformação necessária para estruturar e organizar os dados, e, finalmente, carregar essas informações em um formato acessível e estratégico. Esse processo permite uma análise mais profunda e precisa, gerando relatórios e insights valiosos para tomadas de decisão mais informadas.<br><br>
            Basta carregar o arquivo na seção abaixo e iniciar o processo. O sistema cuidará do resto, facilitando sua análise de dados e garantindo que você obtenha os resultados esperados.
    </p>

    <div v-if="message" class="alert mt-3" :class="messageType">
        {{ message }}
    </div>

      <btn buttonType="primary" @click="openModal">Selecionar Arquivo</btn>
  
      <div
        v-if="isModalOpen"
        class="modal-overlay"
        @click.self="closeModal"
      >
        <div class="modal-content">
          <h2 class="text-center text-primary">Upload de Arquivo PRN</h2>
          <div
            class="drop-zone"
            @dragover.prevent
            @drop.prevent="handleDrop"
            @dragleave="dragging = false"
            :class="{ 'drop-zone--dragging': dragging }"
          >
            <p v-if="!file">Arraste e solte seu arquivo PRN aqui</p>
            <p v-else>Arquivo Selecionado: {{ file.name }}</p>
            <input
              type="file"
              ref="fileInput"
              class="d-none"
              @change="handleFileSelect"
              accept=".prn"
            />
            <button class="btn btn-secondary mt-3" @click="triggerFileInput">
              Escolher Arquivo
            </button>
            
          </div>
  
          <btn
            v-if="file"
            buttonType="primary"
            :isDisabled="uploading"
            @click="uploadFile"
            >
            {{ uploading ? 'Enviando...' : 'Enviar Arquivo' }}
        </btn>
          <btn buttonType="secondary" @click="closeModal">Cancelar</btn>
        </div>
      </div>
    </div>
  </template>
  
  <style scoped>
  .container {
    text-align: center;
  }
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
  }
  .modal-content {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    width: 90%;
    max-width: 500px;
  }
  .drop-zone {
    border: 2px dashed #ccc;
    border-radius: 5px;
    padding: 30px;
    text-align: center;
    background-color: #f9f9f9;
    transition: background-color 0.2s ease;
  }
  .drop-zone--dragging {
    background-color: #e3f2fd;
  }
  .btn {
    margin: 5px;
  }
  .welcome-message {
    font-family: 'Arial', sans-serif;
    font-size: 1.2rem;
    line-height: 1.6;
    color: #333;
    background-color: #f4f4f4;
    padding: 20px;
    border-radius: 10px;
    margin-top: 30px;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.welcome-message br {
    display: block;
    margin: 10px 0;
}

  </style>
  
