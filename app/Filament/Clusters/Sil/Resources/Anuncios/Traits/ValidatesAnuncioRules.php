<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Traits;

use App\Filament\Clusters\Sil\Resources\Anuncios\Enums\Dictamen;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

trait ValidatesAnuncioRules
{
    /**
     * Ejecuta todas las reglas de negocio de Anuncios.
     * Lanza ValidationException si alguna falla.
     *
     * @param array $data  Los datos del formulario.
     */



    protected function validarReglasDeNegocio(array $data): void
    {
        $this->validarProcedenteRequiereNumero($data);
        $this->validarFormatoNumeroAnuncio($data);
        $this->validarFechasVigencia($data);
        $this->validarImprocedenteNoRequiereNumero($data);

        // Agregar más validaciones aquí en el futuro:
        // $this->validarOtraRegla($data);
    }

    /**
     * Un anuncio con dictamen PROCEDENTE debe tener un N° de Anuncio asignado.
     */
    protected function validarProcedenteRequiereNumero(array $data): void
    {
        if (!isset($data['dictamen'])) {
            return;
        }

        $esProcedente = $data['dictamen'] === Dictamen::PROCEDENTE->value
            || $data['dictamen'] === Dictamen::PROCEDENTE;

        $noTieneNumero = !isset($data['n_anuncio'])
            || is_null($data['n_anuncio'])
            || trim((string) $data['n_anuncio']) === '';

        if ($esProcedente && $noTieneNumero) {
            Notification::make()
                ->title('Error de Validación')
                ->body('No se puede guardar un anuncio PROCEDENTE sin un N° de Anuncio asignado.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'n_anuncio' => 'Error: No se puede guardar un anuncio PROCEDENTE sin un N° de Anuncio asignado.',
            ]);
        }
    }

    protected function validarImprocedenteNoRequiereNumero(array $data): void
    {

        if (!isset($data['dictamen'])) {
            return;
        }
        //LOS IMPROCEDENTES Y OBSERVADOS NO DEBEN DE TENER NUMERO

        $esImProcedente = $data['dictamen'] === Dictamen::IMPROCEDENTE->value
            || $data['dictamen'] === Dictamen::IMPROCEDENTE;
        $esObservado = $data['dictamen'] === Dictamen::OBSERVADO->value
            || $data['dictamen'] === Dictamen::OBSERVADO;

        $tieneNumero = isset($data['n_anuncio'])
            || !is_null($data['n_anuncio'])
            || trim((string) $data['n_anuncio']) !== '';

        if (($esImProcedente || $esObservado) && $tieneNumero) {
            Notification::make()
                ->title('Error de Validación')
                ->body('No se puede guardar un anuncio IMPROCEDENTE u OBSERVADO con un N° de Anuncio asignado.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'n_anuncio' => 'Error: No se puede guardar un anuncio IMPROCEDENTE u OBSERVADO con un N° de Anuncio asignado.',
            ]);
        }
    }


    /**
     * El N° de Anuncio debe ser exactamente de 6 dígitos numéricos.
     */
    protected function validarFormatoNumeroAnuncio(array $data): void
    {
        if (empty($data['n_anuncio'])) {
            return;
        }

        if (!preg_match('/^[0-9]{6}$/', $data['n_anuncio'])) {
            Notification::make()
                ->title('Error de Validación')
                ->body('El N° de Anuncio debe contener exactamente 6 dígitos numéricos.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'n_anuncio' => 'El N° de Anuncio debe tener exactamente 6 dígitos.',
            ]);
        }
    }

    /**
     * La Fecha Fin de Vigencia no puede ser menor que la Fecha Inicio de Vigencia.
     */
    protected function validarFechasVigencia(array $data): void
    {
        if (empty($data['fecha_inicio_vigencia']) || empty($data['fecha_fin_vigencia'])) {
            return;
        }

        if ($data['fecha_fin_vigencia'] < $data['fecha_inicio_vigencia']) {
            Notification::make()
                ->title('Error de Validación')
                ->body('La Fecha Fin de Vigencia no puede ser menor que la Fecha Inicio de Vigencia.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'fecha_fin_vigencia' => 'La fecha de fin no puede ser menor a la fecha de inicio.',
            ]);
        }
    }
}
