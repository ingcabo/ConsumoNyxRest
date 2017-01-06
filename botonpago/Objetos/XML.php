<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XML
 *
 * @author arlopez
 */
class XML {
    
    var $control;
    var $codAfiliacion;
    var $factura;
    var $monto;
    var $estado;
    var $codigo;
    var $descripcion;
    var $vtid;
    var $seqnum;
    var $authid;
    var $authname;
    var $tarjeta;
    var $referencia;
    var $terminal;
    var $lote;
    var $rifBanco;
    var $afiliacion;
    var $voucher;
 
    public function getControl() {
        return $this->control;
    }

    public function setControl($control) {
        $this->control = $control;
    }

    public function getCodAfiliacion() {
        return $this->codAfiliacion;
    }

    public function setCodAfiliacion($codAfiliacion) {
        $this->codAfiliacion = $codAfiliacion;
    }

    public function getFactura() {
        return $this->factura;
    }

    public function setFactura($factura) {
        $this->factura = $factura;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getVtid() {
        return $this->vtid;
    }

    public function setVtid($vtid) {
        $this->vtid = $vtid;
    }

    public function getSeqnum() {
        return $this->seqnum;
    }

    public function setSeqnum($seqnum) {
        $this->seqnum = $seqnum;
    }

    public function getAuthid() {
        return $this->authid;
    }

    public function setAuthid($authid) {
        $this->authid = $authid;
    }

    public function getAuthname() {
        return $this->authname;
    }

    public function setAuthname($authname) {
        $this->authname = $authname;
    }

    public function getTarjeta() {
        return $this->tarjeta;
    }

    public function setTarjeta($tarjeta) {
        $this->tarjeta = $tarjeta;
    }

    public function getReferencia() {
        return $this->referencia;
    }

    public function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    public function getTerminal() {
        return $this->terminal;
    }

    public function setTerminal($terminal) {
        $this->terminal = $terminal;
    }

    public function getLote() {
        return $this->lote;
    }

    public function setLote($lote) {
        $this->lote = $lote;
    }

    public function getRifBanco() {
        return $this->rifBanco;
    }

    public function setRifBanco($rifBanco) {
        $this->rifBanco = $rifBanco;
    }

    public function getAfiliacion() {
        return $this->afiliacion;
    }

    public function setAfiliacion($afiliacion) {
        $this->afiliacion = $afiliacion;
    }

    public function getVoucher() {
        return $this->voucher;
    }

    public function setVoucher($voucher) {
        $this->voucher = $voucher;
    }

}

?>
