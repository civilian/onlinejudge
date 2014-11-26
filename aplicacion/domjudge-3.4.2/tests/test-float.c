/*
 * This should fail with WRONG-ANSWER (C doesn't give floating point
 * errors when dividing by zero).
 *
 * @EXPECTED_RESULTS@: WRONG-ANSWER
 */

#include <stdio.h>
#include <math.h>

int main()
{
	double a = M_PI/2;
	double b;

	b = tan(a);
	a = exp(b);

	printf("%lf\n%lf\n%lf\n%lf\n",b,a,1/a,acos(b));

	return 0;
}
