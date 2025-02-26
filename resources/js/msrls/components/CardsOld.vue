<template>
  <div class="p-3 my-3 w-100">
    <div class="row">
      <!-- Card 1 -->
      <div class="col-md-4">
        <div class="card shadow-lg border-0 rounded-3 h-100">
          <div class="card-body">
            <h5
              class="card-title text-center mb-4 fw-bold w-100 p-3 text-white"
              style="background-color: #007bff;"
            >
              Online Application
            </h5>
            <ul class="list-group list-group-flush mb-4">
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-download me-2 text-primary"></i>
                Download Admit Card
              </li>
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-card-checklist me-2 text-primary"></i>
                View Application Status
              </li>
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-info-circle me-2 text-primary"></i>
                Important Instructions
              </li>
            </ul>
            <a href="#" class="btn btn-primary w-100 py-2 text-decoration-none">
              Previous Year Question
            </a>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4">
        <div class="card shadow-lg border-0 rounded-3 h-100">
          <div class="card-body">
            <h5
              class="card-title text-center mb-4 fw-bold w-100 p-3 text-white"
              style="background-color: #28a745;"
            >
              Programme Schedule
            </h5>
            <ul class="list-group list-group-flush mb-4">
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-person-plus me-2 text-success"></i>
                New Registration
              </li>
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-person-lines-fill me-2 text-success"></i>
                Update Details
              </li>
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-gear me-2 text-success"></i>
                Account Settings
              </li>
            </ul>
            <a href="#" class="btn btn-success w-100 py-2 text-decoration-none">
              Start Registration
            </a>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4">
        <div class="card shadow-lg border-0 rounded-3 h-100">
          <div class="card-body">
            <h5
              class="card-title text-center mb-4 fw-bold w-100 p-3 text-white"
              style="background-color: #dc3545;"
            >
              Results
            </h5>
            <ul class="list-group list-group-flush mb-4">
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-journal-check me-2 text-danger"></i>
                Exam Schedules
              </li>
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-calendar-event me-2 text-danger"></i>
                Exam Dates
              </li>
              <li class="list-group-item d-flex align-items-center">
                <i class="bi bi-question-circle me-2 text-danger"></i>
                FAQ
              </li>
            </ul>
            <a href="#" class="btn btn-danger w-100 py-2 text-decoration-none">
              View Exam Details
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  imageSrc: {
    type: String,
    required: true
  },
  title: {
    type: String,
    required: true
  }
});
</script>
