from django.db import models

# Create your models here.
class Equipamento(models.Model):
	cd_equipamento = models.AutoField(primary_key=True)
	ds_equipamento = models.CharField(max_length=100)
	cd_patrimonio  = models.CharField(max_length=50)
	nm_modelo      = models.CharField(max_length=30)
	nm_fabricante  = models.CharField(max_length=20)
	nm_marca       = models.CharField(max_length=20)
	nm_setor       = models.CharField(max_length=20)
	nm_sala        = models.CharField(max_length=15)
	ic_posse       = models.CharField(max_length=10, null=True)
	cd_fiscal      = models.CharField(max_length=20, null=True)
	vl_equipamento = models.FloatField()
	dt_instalacao  = models.DateField()
	dt_garantia    = models.DateField()
	ic_manutencao  = models.CharField(max_length=5, null=True)
	cd_prestador   = models.CharField(max_length=20, null=True)
	ic_tensao      = models.FloatField()
	vl_potencia    = models.FloatField()
	ic_operacao    = models.CharField(max_length=5, null=True)
	ic_tecnico     = models.CharField(max_length=5, null=True)
	ds_insumo      = models.CharField(max_length=100, null=True)
	ds_obs         = models.TextField(null=True)
	ic_delete      = models.IntegerField(default=0)