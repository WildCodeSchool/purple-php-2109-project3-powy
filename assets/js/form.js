/*  Student Form */
// fetch the buttons
const btnStep2 = document.getElementById('btn-step2');
const btnStep3 = document.getElementById('btn-step3');
const btnStep4 = document.getElementById('btn-step4');
const btnStep5 = document.getElementById('btn-step5');
const btnStep6 = document.getElementById('btn-step6');
const returnStep1 = document.getElementById('return-btn-step1');
const returnStep2 = document.getElementById('return-btn-step2');
const returnStep3 = document.getElementById('return-btn-step3');
const returnStep4 = document.getElementById('return-btn-step4');
const returnStep5 = document.getElementById('return-btn-step5');

// fetch the div
const divStep1 = document.getElementById('step-1');
const divStep2 = document.getElementById('step-2');
const divStep3 = document.getElementById('step-3');
const divStep4 = document.getElementById('step-4');
const divStep5 = document.getElementById('step-5');
const divStep6 = document.getElementById('step-6');

// btn listeners
btnStep2.addEventListener('click', () => {
    divStep1.classList.toggle('hidden');
    divStep2.classList.toggle('hidden');
});

btnStep3.addEventListener('click', () => {
    divStep2.classList.toggle('hidden');
    divStep3.classList.toggle('hidden');
});

btnStep4.addEventListener('click', () => {
    divStep3.classList.toggle('hidden');
    divStep4.classList.toggle('hidden');
});

btnStep5.addEventListener('click', () => {
    divStep4.classList.toggle('hidden');
    divStep5.classList.toggle('hidden');
});

btnStep6.addEventListener('click', () => {
    divStep5.classList.toggle('hidden');
    divStep6.classList.toggle('hidden');
});

// return btn listeners
returnStep1.addEventListener('click', () => {
    divStep1.classList.toggle('hidden');
    divStep2.classList.toggle('hidden');
});

returnStep2.addEventListener('click', () => {
    divStep2.classList.toggle('hidden');
    divStep3.classList.toggle('hidden');
});

returnStep3.addEventListener('click', () => {
    divStep3.classList.toggle('hidden');
    divStep4.classList.toggle('hidden');
});

returnStep4.addEventListener('click', () => {
    divStep4.classList.toggle('hidden');
    divStep5.classList.toggle('hidden');
});

returnStep5.addEventListener('click', () => {
    divStep5.classList.toggle('hidden');
    divStep6.classList.toggle('hidden');
});

/*  Mentor Form */
// fetch the buttons
const btnStepMentor2 = document.getElementById('btn-step2-mentor');
const btnStepMentor3 = document.getElementById('btn-step3-mentor');
const btnStepMentor4 = document.getElementById('btn-step4-mentor');
const btnStepMentor5 = document.getElementById('btn-step5-mentor');
const returnStepMentor1 = document.getElementById('return-btn-step1-mentor');
const returnStepMentor2 = document.getElementById('return-btn-step2-mentor');
const returnStepMentor3 = document.getElementById('return-btn-step3-mentor');
const returnStepMentor4 = document.getElementById('return-btn-step4-mentor');

// fetch the div
const divStepMentor1 = document.getElementById('step-1-mentor');
const divStepMentor2 = document.getElementById('step-2-mentor');
const divStepMentor3 = document.getElementById('step-3-mentor');
const divStepMentor4 = document.getElementById('step-4-mentor');
const divStepMentor5 = document.getElementById('step-5-mentor');

// btn listeners
btnStepMentor2.addEventListener('click', () => {
    divStepMentor1.classList.toggle('hidden');
    divStepMentor2.classList.toggle('hidden');
});

btnStepMentor3.addEventListener('click', () => {
    divStepMentor2.classList.toggle('hidden');
    divStepMentor3.classList.toggle('hidden');
});

btnStepMentor4.addEventListener('click', () => {
    divStepMentor3.classList.toggle('hidden');
    divStepMentor4.classList.toggle('hidden');
});

btnStepMentor5.addEventListener('click', () => {
    divStepMentor4.classList.toggle('hidden');
    divStepMentor5.classList.toggle('hidden');
});

// return btn listeners
returnStepMentor1.addEventListener('click', () => {
    divStepMentor1.classList.toggle('hidden');
    divStepMentor2.classList.toggle('hidden');
});

returnStepMentor2.addEventListener('click', () => {
    divStepMentor2.classList.toggle('hidden');
    divStepMentor3.classList.toggle('hidden');
});

returnStepMentor3.addEventListener('click', () => {
    divStepMentor3.classList.toggle('hidden');
    divStepMentor4.classList.toggle('hidden');
});

returnStepMentor4.addEventListener('click', () => {
    divStepMentor4.classList.toggle('hidden');
    divStepMentor5.classList.toggle('hidden');
});
