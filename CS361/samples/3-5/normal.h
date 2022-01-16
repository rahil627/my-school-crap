double normal(const double &mean, const double &sdiv)

{

  static const double pii=3.1415927;

  static const double r_max=RAND_MAX+1;

  return sdiv*sqrt(-2*log((rand()+1)/r_max))*sin(2*pii*rand()/r_max)+mean;

} 



